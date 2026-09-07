<?php

namespace Tests\Feature;

use App\Models\GoogleIndexRequest;
use App\Services\GoogleIndexingService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Covers the bulk submission pipeline: quota, de-duplication and logging.
 * Everything is rolled back, the dev database is left untouched.
 */
class GoogleIndexingSubmissionTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::forget(GoogleIndexingService::TOKEN_CACHE_KEY);

        openssl_pkey_export(openssl_pkey_new([
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]), $privateKey);

        config([
            'google-indexing.credentials_json' => json_encode([
                'type'         => 'service_account',
                'project_id'   => 'test-project',
                'client_email' => 'indexer@test-project.iam.gserviceaccount.com',
                'private_key'  => $privateKey,
            ]),
            'google-indexing.allowed_hosts'      => ['example.com'],
            'google-indexing.daily_quota'        => 5,
            'google-indexing.batch_size'         => 100,
            'google-indexing.resubmit_after_days' => 7,
        ]);

        GoogleIndexRequest::query()->delete();

        $this->fakeGoogle();
    }

    protected function fakeGoogle(): void
    {
        Http::fake([
            'oauth2.googleapis.com/*' => Http::response(['access_token' => 'test-token', 'expires_in' => 3600]),
            'indexing.googleapis.com/batch' => function ($request) {
                preg_match_all('/\{"url":"(.*?)","type"/', $request->body(), $matches);

                $body = '';

                foreach ($matches[1] as $index => $url) {
                    $url = stripslashes($url);
                    $payload = json_encode(['urlNotificationMetadata' => [
                        'url' => $url,
                        'latestUpdate' => ['url' => $url, 'type' => 'URL_UPDATED', 'notifyTime' => '2026-08-21T10:00:00.000000Z'],
                    ]]);

                    $body .= "--batch_x\r\nContent-Type: application/http\r\nContent-ID: <response-item-{$index}>\r\n\r\n"
                        . "HTTP/1.1 200 OK\r\nContent-Type: application/json\r\n\r\n{$payload}\r\n\r\n";
                }

                return Http::response($body . "--batch_x--\r\n", 200, ['Content-Type' => 'multipart/mixed; boundary=batch_x']);
            },
        ]);
    }

    /** @test */
    public function it_submits_urls_and_writes_a_log_row_for_each_one(): void
    {
        $summary = app(GoogleIndexingService::class)->submit([
            'https://example.com/one',
            'https://example.com/two',
        ], ['source' => 'test']);

        $this->assertSame(2, $summary['submitted']);
        $this->assertSame(2, $summary['success']);
        $this->assertSame(0, $summary['failed']);

        $this->assertSame(2, GoogleIndexRequest::successful()->count());

        $row = GoogleIndexRequest::forUrl('https://example.com/one')->first();
        $this->assertNotNull($row);
        $this->assertSame('URL_UPDATED', $row->type);
        $this->assertSame('test', $row->source);
        $this->assertSame(200, $row->http_code);
        $this->assertSame('2026-08-21 10:00:00', $row->notify_time->format('Y-m-d H:i:s'));
        $this->assertSame($summary['batch_id'], $row->batch_id);
    }

    /** @test */
    public function it_skips_urls_that_were_already_submitted_inside_the_resubmit_window(): void
    {
        $service = app(GoogleIndexingService::class);

        $service->submit(['https://example.com/one'], ['source' => 'test']);
        $second = $service->submit(['https://example.com/one', 'https://example.com/two'], ['source' => 'test']);

        $this->assertSame(1, $second['submitted']);
        $this->assertSame(1, $second['skipped']);
        $this->assertSame(2, GoogleIndexRequest::successful()->count());

        // ...unless the caller explicitly forces a resend.
        $forced = $service->submit(['https://example.com/one'], ['source' => 'test', 'force' => true]);
        $this->assertSame(1, $forced['submitted']);
        $this->assertSame(0, $forced['skipped']);
    }

    /** @test */
    public function it_stops_at_the_daily_quota_and_skips_the_rest(): void
    {
        $urls = array_map(fn ($i) => 'https://example.com/page-' . $i, range(1, 8));

        $summary = app(GoogleIndexingService::class)->submit($urls, ['source' => 'test']);

        $this->assertSame(5, $summary['submitted'], 'daily_quota is 5');
        $this->assertSame(3, $summary['skipped']);
        $this->assertSame(0, $summary['quota_left']);
        $this->assertStringContainsString('Daily quota reached', $summary['errors'][0]);
        $this->assertSame(5, GoogleIndexRequest::billable()->count());
    }

    /** @test */
    public function it_splits_large_lists_into_batches_of_the_configured_size(): void
    {
        config(['google-indexing.daily_quota' => 500, 'google-indexing.batch_size' => 2]);

        $urls = array_map(fn ($i) => 'https://example.com/page-' . $i, range(1, 5));

        $summary = app(GoogleIndexingService::class)->submit($urls, ['source' => 'test']);

        $this->assertSame(5, $summary['success']);

        // 1 token call + ceil(5 / 2) = 3 batch calls.
        Http::assertSentCount(4);
    }

    /** @test */
    public function a_dry_run_never_calls_google_and_never_logs(): void
    {
        $summary = app(GoogleIndexingService::class)->submit(
            ['https://example.com/one'],
            ['source' => 'test', 'dry_run' => true]
        );

        $this->assertTrue($summary['dry_run']);
        $this->assertSame(['https://example.com/one'], $summary['pending']);
        $this->assertSame(0, GoogleIndexRequest::count());

        Http::assertNothingSent();
    }
}
