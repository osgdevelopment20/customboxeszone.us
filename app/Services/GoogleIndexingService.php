<?php

namespace App\Services;

use App\Models\GoogleIndexRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Bulk submission of URLs to the Google Indexing API (v3).
 *
 * Authentication is done with a service account JWT (RS256) exchanged for an
 * OAuth access token, so no extra composer package is required. URLs are sent
 * in multipart/mixed batches of up to 100 calls per HTTP request.
 *
 * @see https://developers.google.com/search/apis/indexing-api/v3/using-api
 */
class GoogleIndexingService
{
    public const PUBLISH_ENDPOINT  = 'https://indexing.googleapis.com/v3/urlNotifications:publish';
    public const METADATA_ENDPOINT = 'https://indexing.googleapis.com/v3/urlNotifications/metadata';
    public const BATCH_ENDPOINT    = 'https://indexing.googleapis.com/batch';
    public const TOKEN_ENDPOINT    = 'https://oauth2.googleapis.com/token';
    public const SCOPE             = 'https://www.googleapis.com/auth/indexing';

    public const TYPE_UPDATED = 'URL_UPDATED';
    public const TYPE_DELETED = 'URL_DELETED';

    /** Hard limit imposed by Google on a single batch request. */
    public const MAX_BATCH_SIZE = 100;

    public const TOKEN_CACHE_KEY = 'google_indexing.access_token';

    protected ?array $credentials = null;

    /* -----------------------------------------------------------------
     |  Settings
     | -----------------------------------------------------------------
     */

    /**
     * Read a module setting, preferring the value stored in the settings table.
     */
    public function setting(string $key, $default = null)
    {
        $value = null;

        if (function_exists('sys_settings')) {
            $value = sys_settings('google_indexing_' . $key);
        }

        if ($value === null || $value === '') {
            return config('google-indexing.' . $key, $default);
        }

        return $value;
    }

    public function dailyQuota(): int
    {
        return max(1, (int) $this->setting('daily_quota', 200));
    }

    public function batchSize(): int
    {
        return max(1, min(self::MAX_BATCH_SIZE, (int) $this->setting('batch_size', 100)));
    }

    public function resubmitAfterDays(): int
    {
        return max(0, (int) $this->setting('resubmit_after_days', 7));
    }

    /**
     * Publish calls already spent today (successes and failures both count).
     */
    public function usedQuotaToday(): int
    {
        return GoogleIndexRequest::billable()
            ->whereDate('submitted_at', Carbon::today())
            ->count();
    }

    public function remainingQuotaToday(): int
    {
        return max(0, $this->dailyQuota() - $this->usedQuotaToday());
    }

    /* -----------------------------------------------------------------
     |  Credentials & authentication
     | -----------------------------------------------------------------
     */

    public function credentialsPath(): string
    {
        return (string) config('google-indexing.credentials_path');
    }

    public function hasCredentials(): bool
    {
        try {
            $this->credentials();

            return true;
        } catch (RuntimeException $e) {
            return false;
        }
    }

    /**
     * The service account e-mail that has to be added as an owner in Search Console.
     */
    public function serviceAccountEmail(): ?string
    {
        try {
            return $this->credentials()['client_email'] ?? null;
        } catch (RuntimeException $e) {
            return null;
        }
    }

    /**
     * @throws RuntimeException when no usable service account key is available
     */
    public function credentials(): array
    {
        if ($this->credentials !== null) {
            return $this->credentials;
        }

        $raw = null;
        $inline = config('google-indexing.credentials_json');

        if (! empty($inline)) {
            $decoded = base64_decode($inline, true);
            $raw = ($decoded !== false && str_starts_with(trim((string) $decoded), '{')) ? $decoded : $inline;
        } elseif (is_file($this->credentialsPath())) {
            $raw = file_get_contents($this->credentialsPath());
        }

        if (empty($raw)) {
            throw new RuntimeException('Google Indexing API credentials are missing. Upload the service account JSON key first.');
        }

        $credentials = json_decode((string) $raw, true);

        if (! is_array($credentials)) {
            throw new RuntimeException('The service account key is not valid JSON.');
        }

        foreach (['client_email', 'private_key'] as $field) {
            if (empty($credentials[$field])) {
                throw new RuntimeException("The service account key is missing the \"{$field}\" field.");
            }
        }

        return $this->credentials = $credentials;
    }

    /**
     * Store an uploaded service account key and reset any cached token.
     *
     * @throws RuntimeException
     */
    public function storeCredentials(string $json): array
    {
        $credentials = json_decode($json, true);

        if (! is_array($credentials) || empty($credentials['client_email']) || empty($credentials['private_key'])) {
            throw new RuntimeException('That file does not look like a Google service account key (client_email / private_key missing).');
        }

        if (($credentials['type'] ?? null) !== 'service_account') {
            throw new RuntimeException('Only service account keys are supported by the Indexing API.');
        }

        $path = $this->credentialsPath();

        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        file_put_contents($path, json_encode($credentials, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        @chmod($path, 0600);

        $this->credentials = $credentials;
        $this->forgetToken();

        return $credentials;
    }

    public function deleteCredentials(): void
    {
        if (is_file($this->credentialsPath())) {
            @unlink($this->credentialsPath());
        }

        $this->credentials = null;
        $this->forgetToken();
    }

    public function forgetToken(): void
    {
        Cache::forget(self::TOKEN_CACHE_KEY);
    }

    /**
     * Exchange the service account JWT for an OAuth access token (cached).
     *
     * @throws RuntimeException
     */
    public function accessToken(bool $fresh = false): string
    {
        if ($fresh) {
            $this->forgetToken();
        }

        $cached = Cache::get(self::TOKEN_CACHE_KEY);

        if (is_string($cached) && $cached !== '') {
            return $cached;
        }

        $credentials = $this->credentials();
        $tokenUri = $credentials['token_uri'] ?? self::TOKEN_ENDPOINT;
        $now = time();

        $assertion = $this->signJwt([
            'iss'   => $credentials['client_email'],
            'scope' => self::SCOPE,
            'aud'   => $tokenUri,
            'exp'   => $now + 3600,
            'iat'   => $now,
        ], $credentials['private_key']);

        $response = Http::asForm()
            ->timeout((int) $this->setting('timeout', 60))
            ->post($tokenUri, [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion'  => $assertion,
            ]);

        if ($response->failed() || empty($response->json('access_token'))) {
            $error = $response->json('error_description') ?? $response->json('error') ?? $response->body();

            throw new RuntimeException('Could not obtain a Google access token: ' . (is_string($error) ? $error : json_encode($error)));
        }

        $token = (string) $response->json('access_token');
        $ttl = max(60, (int) ($response->json('expires_in') ?? 3600) - 120);

        Cache::put(self::TOKEN_CACHE_KEY, $token, $ttl);

        return $token;
    }

    /**
     * Build an RS256 signed JWT without pulling in an external JWT package.
     */
    protected function signJwt(array $claims, string $privateKey): string
    {
        $segments = [
            $this->base64UrlEncode(json_encode(['alg' => 'RS256', 'typ' => 'JWT'])),
            $this->base64UrlEncode(json_encode($claims)),
        ];

        $signingInput = implode('.', $segments);
        $key = openssl_pkey_get_private($privateKey);

        if ($key === false) {
            throw new RuntimeException('The private key in the service account file could not be read: ' . openssl_error_string());
        }

        $signature = '';

        if (! openssl_sign($signingInput, $signature, $key, OPENSSL_ALGO_SHA256)) {
            throw new RuntimeException('Unable to sign the Google API request: ' . openssl_error_string());
        }

        $segments[] = $this->base64UrlEncode($signature);

        return implode('.', $segments);
    }

    protected function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    /**
     * Cheap credential check used by the "Test connection" button.
     */
    public function testConnection(): array
    {
        $this->accessToken(true);

        return [
            'ok'      => true,
            'email'   => $this->serviceAccountEmail(),
            'project' => $this->credentials()['project_id'] ?? null,
        ];
    }

    /* -----------------------------------------------------------------
     |  Publishing
     | -----------------------------------------------------------------
     */

    /**
     * Publish a single URL. Returns the normalised result array.
     */
    public function publish(string $url, string $type = self::TYPE_UPDATED): array
    {
        $response = Http::withToken($this->accessToken())
            ->timeout((int) $this->setting('timeout', 60))
            ->post(self::PUBLISH_ENDPOINT, ['url' => $url, 'type' => $type]);

        return $this->normaliseResult($url, $response->status(), $response->json() ?? $response->body());
    }

    /**
     * Publish up to 100 URLs with one multipart/mixed batch request.
     *
     * @param  string[]  $urls
     * @return array[]   one normalised result per URL, in the given order
     */
    public function publishBatch(array $urls, string $type = self::TYPE_UPDATED): array
    {
        $urls = array_values($urls);

        if (empty($urls)) {
            return [];
        }

        if (count($urls) > self::MAX_BATCH_SIZE) {
            throw new RuntimeException('A batch request may not contain more than ' . self::MAX_BATCH_SIZE . ' URLs.');
        }

        $boundary = 'cbz_batch_' . Str::random(24);

        $response = Http::withToken($this->accessToken())
            ->withHeaders(['Accept' => 'multipart/mixed'])
            ->withBody($this->buildBatchBody($urls, $type, $boundary), 'multipart/mixed; boundary=' . $boundary)
            ->timeout((int) $this->setting('timeout', 60))
            ->post(self::BATCH_ENDPOINT);

        // A transport/auth level failure fails the whole batch at once.
        if ($response->failed() && ! Str::contains((string) $response->header('Content-Type'), 'multipart')) {
            $error = $response->json('error.message') ?? $response->body();

            return array_map(
                fn ($url) => $this->normaliseResult($url, $response->status(), $response->json() ?? $response->body(), $error),
                $urls
            );
        }

        return $this->parseBatchResponse($response->body(), (string) $response->header('Content-Type'), $urls, $response->status());
    }

    /**
     * @param  string[]  $urls
     */
    protected function buildBatchBody(array $urls, string $type, string $boundary): string
    {
        $body = '';

        foreach ($urls as $index => $url) {
            $payload = json_encode(['url' => $url, 'type' => $type]);

            $body .= "--{$boundary}\r\n";
            $body .= "Content-Type: application/http\r\n";
            $body .= "Content-Transfer-Encoding: binary\r\n";
            $body .= "Content-ID: <item-{$index}>\r\n";
            $body .= "\r\n";
            $body .= "POST /v3/urlNotifications:publish HTTP/1.1\r\n";
            $body .= "Content-Type: application/json\r\n";
            $body .= "Accept: application/json\r\n";
            $body .= 'Content-Length: ' . strlen($payload) . "\r\n";
            $body .= "\r\n";
            $body .= $payload . "\r\n";
        }

        return $body . "--{$boundary}--\r\n";
    }

    /**
     * Split the multipart/mixed answer back into one result per submitted URL.
     *
     * @param  string[]  $urls
     * @return array[]
     */
    protected function parseBatchResponse(string $body, string $contentType, array $urls, int $status): array
    {
        $results = [];
        $boundary = null;

        if (preg_match('/boundary=(?:"([^"]+)"|([^;\s]+))/i', $contentType, $matches)) {
            $boundary = $matches[1] !== '' ? $matches[1] : ($matches[2] ?? null);
        }

        if (! $boundary) {
            // Nothing parseable came back - treat every URL as failed.
            return array_map(
                fn ($url) => $this->normaliseResult($url, $status, $body, 'Unreadable batch response from Google.'),
                $urls
            );
        }

        $position = 0;

        foreach (explode('--' . $boundary, $body) as $part) {
            $part = ltrim($part, "\r\n");

            if ($part === '' || str_starts_with($part, '--')) {
                continue;
            }

            $split = preg_split("/\r\n\r\n/", $part, 2);

            if (count($split) < 2) {
                continue;
            }

            [$partHeaders, $httpResponse] = $split;

            $index = $position;

            if (preg_match('/Content-ID:\s*<?response-item-(\d+)>?/i', $partHeaders, $idMatch)) {
                $index = (int) $idMatch[1];
            }

            $position++;

            if (! isset($urls[$index])) {
                continue;
            }

            $code = $status;
            $payload = $httpResponse;

            $inner = preg_split("/\r\n\r\n/", $httpResponse, 2);

            if (preg_match('#^HTTP/\d\.\d\s+(\d{3})#i', $httpResponse, $codeMatch)) {
                $code = (int) $codeMatch[1];
            }

            if (count($inner) === 2) {
                $payload = trim($inner[1]);
            }

            $decoded = json_decode($payload, true);

            $results[$index] = $this->normaliseResult($urls[$index], $code, is_array($decoded) ? $decoded : $payload);
        }

        // Any URL Google did not answer for is reported as failed rather than silently dropped.
        foreach ($urls as $index => $url) {
            if (! isset($results[$index])) {
                $results[$index] = $this->normaliseResult($url, $status, null, 'No response part returned for this URL.');
            }
        }

        ksort($results);

        return array_values($results);
    }

    /**
     * Turn any Google answer into the same shape used across the module.
     */
    protected function normaliseResult(string $url, int $status, $body, ?string $error = null): array
    {
        $success = $status >= 200 && $status < 300 && $error === null;
        $message = $error;

        if (! $success && $message === null) {
            $message = is_array($body)
                ? ($body['error']['message'] ?? json_encode($body))
                : (string) $body;
        }

        $notifyTime = null;

        if (is_array($body)) {
            $notifyTime = $body['urlNotificationMetadata']['latestUpdate']['notifyTime']
                ?? $body['urlNotificationMetadata']['latestRemove']['notifyTime']
                ?? null;
        }

        return [
            'url'         => $url,
            'success'     => $success,
            'status'      => $status,
            'notify_time' => $notifyTime,
            'message'     => $success ? null : Str::limit((string) $message, 500),
            'body'        => $body,
        ];
    }

    /* -----------------------------------------------------------------
     |  High level bulk submission
     | -----------------------------------------------------------------
     */

    /**
     * Submit a list of URLs, honouring quota, de-duplication and batching.
     *
     * Options: type, source, force (ignore the resubmit window), dry_run,
     * limit, progress (callable receiving each chunk summary).
     *
     * @param  string[]  $urls
     */
    public function submit(array $urls, array $options = []): array
    {
        $type     = $options['type'] ?? self::TYPE_UPDATED;
        $source   = $options['source'] ?? 'manual';
        $force    = (bool) ($options['force'] ?? false);
        $dryRun   = (bool) ($options['dry_run'] ?? false);
        $progress = $options['progress'] ?? null;
        $batchId  = $options['batch_id'] ?? (string) Str::uuid();

        $summary = [
            'batch_id'  => $batchId,
            'submitted' => 0,
            'success'   => 0,
            'failed'    => 0,
            'skipped'   => 0,
            'quota_left' => $this->remainingQuotaToday(),
            'results'   => [],
            'errors'    => [],
        ];

        [$valid, $invalid] = $this->partitionValidUrls($urls);

        foreach ($invalid as $url => $reason) {
            $summary['skipped']++;
            $summary['results'][] = ['url' => $url, 'status' => GoogleIndexRequest::STATUS_SKIPPED, 'message' => $reason];
        }

        if (! $force) {
            $recent = $this->recentlySubmitted($valid, $type);

            foreach ($recent as $url => $submittedAt) {
                $summary['skipped']++;
                $summary['results'][] = [
                    'url'     => $url,
                    'status'  => GoogleIndexRequest::STATUS_SKIPPED,
                    'message' => 'Already submitted on ' . $submittedAt->format('d M Y H:i'),
                ];
            }

            $valid = array_values(array_diff($valid, array_keys($recent)));
        }

        if (! empty($options['limit'])) {
            $valid = array_slice($valid, 0, (int) $options['limit']);
        }

        $remaining = $this->remainingQuotaToday();

        if (! $dryRun && count($valid) > $remaining) {
            foreach (array_slice($valid, $remaining) as $url) {
                $summary['skipped']++;
                $summary['results'][] = [
                    'url'     => $url,
                    'status'  => GoogleIndexRequest::STATUS_SKIPPED,
                    'message' => 'Daily quota reached (' . $this->dailyQuota() . ' URLs/day).',
                ];
            }

            $valid = array_slice($valid, 0, $remaining);
            $summary['errors'][] = 'Daily quota reached - the remaining URLs were skipped and can be sent tomorrow.';
        }

        if ($dryRun) {
            $summary['dry_run'] = true;
            $summary['pending'] = $valid;

            foreach ($valid as $url) {
                $summary['results'][] = ['url' => $url, 'status' => 'pending', 'message' => 'Dry run - nothing was sent.'];
            }

            return $summary;
        }

        $useBatch = (bool) $this->setting('use_batch', true);

        foreach (array_chunk($valid, $useBatch ? $this->batchSize() : 1) as $chunk) {
            try {
                $results = $useBatch ? $this->publishBatch($chunk, $type) : [$this->publish($chunk[0], $type)];
            } catch (\Throwable $e) {
                Log::error('Google Indexing API batch failed: ' . $e->getMessage());

                $summary['errors'][] = $e->getMessage();
                $results = array_map(
                    fn ($url) => $this->normaliseResult($url, 0, null, $e->getMessage()),
                    $chunk
                );
            }

            foreach ($results as $result) {
                $this->log($result, $type, $source, $batchId);

                $summary['submitted']++;
                $result['success'] ? $summary['success']++ : $summary['failed']++;

                $summary['results'][] = [
                    'url'     => $result['url'],
                    'status'  => $result['success'] ? GoogleIndexRequest::STATUS_SUCCESS : GoogleIndexRequest::STATUS_FAILED,
                    'code'    => $result['status'],
                    'message' => $result['message'],
                ];
            }

            if (is_callable($progress)) {
                $progress($results);
            }
        }

        $summary['quota_left'] = $this->remainingQuotaToday();

        return $summary;
    }

    /**
     * Ask Google when it last saw a URL.
     */
    public function metadata(string $url): array
    {
        $response = Http::withToken($this->accessToken())
            ->timeout((int) $this->setting('timeout', 60))
            ->get(self::METADATA_ENDPOINT, ['url' => $url]);

        return [
            'ok'     => $response->successful(),
            'status' => $response->status(),
            'body'   => $response->json() ?? $response->body(),
        ];
    }

    /* -----------------------------------------------------------------
     |  Helpers
     | -----------------------------------------------------------------
     */

    /**
     * @param  string[]  $urls
     * @return array{0: string[], 1: array<string, string>}
     */
    public function partitionValidUrls(array $urls): array
    {
        $allowedHosts = array_map('strtolower', (array) config('google-indexing.allowed_hosts', []));
        $valid = [];
        $invalid = [];

        foreach ($urls as $url) {
            $url = trim((string) $url);

            if ($url === '' || isset($invalid[$url]) || in_array($url, $valid, true)) {
                continue;
            }

            if (! filter_var($url, FILTER_VALIDATE_URL) || ! Str::startsWith($url, ['http://', 'https://'])) {
                $invalid[$url] = 'Not a valid absolute URL.';
                continue;
            }

            $host = strtolower((string) parse_url($url, PHP_URL_HOST));

            if (! empty($allowedHosts) && ! in_array($host, $allowedHosts, true)) {
                $invalid[$url] = 'Host "' . $host . '" is not one of the allowed hosts.';
                continue;
            }

            $valid[] = $url;
        }

        return [$valid, $invalid];
    }

    /**
     * URLs already published successfully inside the resubmit window.
     *
     * @param  string[]  $urls
     * @return array<string, Carbon>
     */
    public function recentlySubmitted(array $urls, string $type = self::TYPE_UPDATED): array
    {
        $days = $this->resubmitAfterDays();

        if ($days <= 0 || empty($urls)) {
            return [];
        }

        $found = [];

        foreach (array_chunk($urls, 500) as $chunk) {
            $rows = GoogleIndexRequest::successful()
                ->where('type', $type)
                ->whereIn('url_hash', array_map([GoogleIndexRequest::class, 'hash'], $chunk))
                ->where('submitted_at', '>=', Carbon::now()->subDays($days))
                ->get(['url', 'submitted_at']);

            foreach ($rows as $row) {
                $found[$row->url] = $row->submitted_at;
            }
        }

        return $found;
    }

    protected function log(array $result, string $type, string $source, string $batchId): GoogleIndexRequest
    {
        return GoogleIndexRequest::create([
            'url'          => $result['url'],
            'type'         => $type,
            'source'       => $source,
            'status'       => $result['success'] ? GoogleIndexRequest::STATUS_SUCCESS : GoogleIndexRequest::STATUS_FAILED,
            'http_code'    => $result['status'] ?: null,
            'batch_id'     => $batchId,
            'notify_time'  => $result['notify_time'] ? Carbon::parse($result['notify_time']) : null,
            'message'      => $result['message'],
            'response'     => is_array($result['body']) ? json_encode($result['body']) : Str::limit((string) $result['body'], 2000),
            'submitted_at' => Carbon::now(),
        ]);
    }
}
