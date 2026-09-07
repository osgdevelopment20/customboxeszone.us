<?php

namespace Tests\Feature;

use App\Services\GoogleIndexingService;
use App\Services\IndexableUrlCollector;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GoogleIndexingServiceTest extends TestCase
{
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
            'google-indexing.allowed_hosts' => ['example.com'],
        ]);
    }

    /**
     * Build a fake multipart/mixed answer like the batch endpoint returns.
     */
    protected function batchResponse(array $parts, string $boundary = 'batch_test_boundary'): string
    {
        $body = '';

        foreach ($parts as $index => $part) {
            $payload = json_encode($part['body']);

            $body .= "--{$boundary}\r\n";
            $body .= "Content-Type: application/http\r\n";
            $body .= 'Content-ID: <response-item-' . ($part['id'] ?? $index) . ">\r\n";
            $body .= "\r\n";
            $body .= 'HTTP/1.1 ' . $part['code'] . " OK\r\n";
            $body .= "Content-Type: application/json; charset=UTF-8\r\n";
            $body .= "\r\n";
            $body .= $payload . "\r\n";
            $body .= "\r\n";
        }

        return $body . "--{$boundary}--\r\n";
    }

    protected function fakeToken(): void
    {
        Http::fake([
            'oauth2.googleapis.com/*' => Http::response(['access_token' => 'test-token', 'expires_in' => 3600]),
        ]);
    }

    /** @test */
    public function it_signs_a_jwt_and_exchanges_it_for_an_access_token(): void
    {
        $this->fakeToken();

        $token = app(GoogleIndexingService::class)->accessToken();

        $this->assertSame('test-token', $token);

        Http::assertSent(function ($request) {
            $assertion = $request['assertion'] ?? '';
            [$header, $claims] = array_map(
                fn ($segment) => json_decode(base64_decode(strtr($segment, '-_', '+/')), true),
                array_slice(explode('.', $assertion), 0, 2)
            );

            return count(explode('.', $assertion)) === 3
                && $header['alg'] === 'RS256'
                && $claims['scope'] === GoogleIndexingService::SCOPE
                && $claims['iss'] === 'indexer@test-project.iam.gserviceaccount.com';
        });
    }

    /** @test */
    public function it_sends_one_multipart_batch_request_for_many_urls(): void
    {
        $urls = ['https://example.com/a', 'https://example.com/b', 'https://example.com/c'];

        Http::fake([
            'oauth2.googleapis.com/*' => Http::response(['access_token' => 'test-token', 'expires_in' => 3600]),
            'indexing.googleapis.com/batch' => Http::response(
                $this->batchResponse(array_map(fn ($url) => [
                    'code' => 200,
                    'body' => ['urlNotificationMetadata' => [
                        'url' => $url,
                        'latestUpdate' => ['url' => $url, 'type' => 'URL_UPDATED', 'notifyTime' => '2026-08-21T10:00:00.000000Z'],
                    ]],
                ], $urls)),
                200,
                ['Content-Type' => 'multipart/mixed; boundary=batch_test_boundary']
            ),
        ]);

        $results = app(GoogleIndexingService::class)->publishBatch($urls);

        $this->assertCount(3, $results);
        $this->assertSame($urls, array_column($results, 'url'));
        $this->assertEquals([true, true, true], array_column($results, 'success'));
        $this->assertSame('2026-08-21T10:00:00.000000Z', $results[0]['notify_time']);

        // One HTTP call for the token + exactly one for all three URLs.
        Http::assertSentCount(2);

        Http::assertSent(function ($request) {
            if (! str_contains($request->url(), '/batch')) {
                return false;
            }

            $body = $request->body();

            return str_contains($request->header('Content-Type')[0], 'multipart/mixed; boundary=')
                && substr_count($body, 'POST /v3/urlNotifications:publish') === 3
                && substr_count($body, 'Content-Type: application/http') === 3
                && str_contains($body, '{"url":"https:\/\/example.com\/a","type":"URL_UPDATED"}')
                && str_contains($body, 'Content-ID: <item-2>');
        });
    }

    /** @test */
    public function it_maps_out_of_order_and_failed_parts_back_to_the_right_url(): void
    {
        $urls = ['https://example.com/a', 'https://example.com/b'];

        Http::fake([
            'oauth2.googleapis.com/*' => Http::response(['access_token' => 'test-token', 'expires_in' => 3600]),
            'indexing.googleapis.com/batch' => Http::response(
                $this->batchResponse([
                    ['id' => 1, 'code' => 429, 'body' => ['error' => ['code' => 429, 'message' => 'Quota exceeded for quota metric.']]],
                    ['id' => 0, 'code' => 200, 'body' => ['urlNotificationMetadata' => ['url' => $urls[0]]]],
                ]),
                200,
                ['Content-Type' => 'multipart/mixed; boundary=batch_test_boundary']
            ),
        ]);

        $results = app(GoogleIndexingService::class)->publishBatch($urls);

        $this->assertSame($urls[0], $results[0]['url']);
        $this->assertTrue($results[0]['success']);

        $this->assertSame($urls[1], $results[1]['url']);
        $this->assertFalse($results[1]['success']);
        $this->assertSame(429, $results[1]['status']);
        $this->assertStringContainsString('Quota exceeded', $results[1]['message']);
    }

    /** @test */
    public function it_fails_every_url_when_the_batch_call_itself_is_rejected(): void
    {
        Http::fake([
            'oauth2.googleapis.com/*' => Http::response(['access_token' => 'test-token', 'expires_in' => 3600]),
            'indexing.googleapis.com/batch' => Http::response(
                ['error' => ['code' => 403, 'message' => 'Permission denied. Failed to verify the URL ownership.']],
                403
            ),
        ]);

        $results = app(GoogleIndexingService::class)->publishBatch(['https://example.com/a', 'https://example.com/b']);

        $this->assertCount(2, $results);

        foreach ($results as $result) {
            $this->assertFalse($result['success']);
            $this->assertStringContainsString('Permission denied', $result['message']);
        }
    }

    /** @test */
    public function it_reports_urls_google_did_not_answer_for(): void
    {
        $urls = ['https://example.com/a', 'https://example.com/b'];

        Http::fake([
            'oauth2.googleapis.com/*' => Http::response(['access_token' => 'test-token', 'expires_in' => 3600]),
            'indexing.googleapis.com/batch' => Http::response(
                $this->batchResponse([['id' => 0, 'code' => 200, 'body' => ['urlNotificationMetadata' => ['url' => $urls[0]]]]]),
                200,
                ['Content-Type' => 'multipart/mixed; boundary=batch_test_boundary']
            ),
        ]);

        $results = app(GoogleIndexingService::class)->publishBatch($urls);

        $this->assertTrue($results[0]['success']);
        $this->assertFalse($results[1]['success']);
        $this->assertStringContainsString('No response part', $results[1]['message']);
    }

    /** @test */
    public function it_refuses_batches_larger_than_the_google_limit(): void
    {
        $this->expectExceptionMessage('may not contain more than 100 URLs');

        app(GoogleIndexingService::class)->publishBatch(
            array_map(fn ($i) => 'https://example.com/page-' . $i, range(1, 101))
        );
    }

    /** @test */
    public function it_filters_out_invalid_urls_and_foreign_hosts(): void
    {
        [$valid, $invalid] = app(GoogleIndexingService::class)->partitionValidUrls([
            'https://example.com/keep',
            'https://example.com/keep',   // duplicate
            'https://not-my-site.com/x',  // wrong host
            '/relative/path',             // not absolute
            'ftp://example.com/file',     // wrong scheme
            '',
        ]);

        $this->assertSame(['https://example.com/keep'], $valid);
        $this->assertArrayHasKey('https://not-my-site.com/x', $invalid);
        $this->assertArrayHasKey('/relative/path', $invalid);
        $this->assertArrayHasKey('ftp://example.com/file', $invalid);
    }

    /** @test */
    public function it_reads_urls_from_a_sitemap_and_follows_a_sitemap_index(): void
    {
        $collector = app(IndexableUrlCollector::class);

        $urlset = '<?xml version="1.0" encoding="UTF-8"?>
            <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
                <url><loc>https://example.com/one</loc><lastmod>2026-08-01</lastmod></url>
                <url><loc>https://example.com/two</loc></url>
            </urlset>';

        $this->assertSame(['https://example.com/one', 'https://example.com/two'], $collector->parseSitemap($urlset));

        Http::fake(['https://example.com/child.xml' => Http::response($urlset)]);

        $index = '<?xml version="1.0" encoding="UTF-8"?>
            <sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
                <sitemap><loc>https://example.com/child.xml</loc></sitemap>
            </sitemapindex>';

        $this->assertSame(['https://example.com/one', 'https://example.com/two'], $collector->parseSitemap($index));
    }

    /** @test */
    public function it_cleans_up_manually_pasted_urls(): void
    {
        $urls = app(IndexableUrlCollector::class)->fromText(
            "https://example.com/a\r\n  https://example.com/b  \n\nnot-a-url\nhttps://example.com/a\n"
        );

        $this->assertSame(['https://example.com/a', 'https://example.com/b'], array_values($urls));
    }
}
