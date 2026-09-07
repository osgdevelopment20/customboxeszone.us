<?php

namespace Tests\Feature;

use App\Models\GoogleIndexRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GoogleIndexingDashboardTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        // Pinned so the test does not depend on this install's APP_URL host.
        config(['google-indexing.allowed_hosts' => ['example.com']]);
    }

    protected function admin(): User
    {
        return User::factory()->create();
    }

    /** @test */
    public function the_dashboard_page_renders(): void
    {
        GoogleIndexRequest::create([
            'url'          => 'https://example.com/rendered',
            'type'         => 'URL_UPDATED',
            'source'       => 'test',
            'status'       => GoogleIndexRequest::STATUS_SUCCESS,
            'http_code'    => 200,
            'submitted_at' => now(),
        ]);

        $this->actingAs($this->admin())
            ->get(route('dashboard.google-indexing.index'))
            ->assertOk()
            ->assertSee('Bulk submit URLs')
            ->assertSee('Quota used today')
            ->assertSee('https://example.com/rendered');
    }

    /** @test */
    public function the_page_is_behind_authentication(): void
    {
        $this->get(route('dashboard.google-indexing.index'))->assertRedirect(route('login'));
    }

    /** @test */
    public function collecting_urls_returns_the_pending_list_without_calling_google(): void
    {
        Http::fake();

        $this->actingAs($this->admin())
            ->postJson(route('dashboard.google-indexing.collect'), [
                'source' => 'manual',
                'urls'   => "https://example.com/a\nhttps://example.com/b\nnope",
            ])
            ->assertOk()
            ->assertJson([
                'ok'      => true,
                'pending' => ['https://example.com/a', 'https://example.com/b'],
                'skipped' => 0,
            ]);

        Http::assertNothingSent();
    }

    /** @test */
    public function submitting_more_than_one_batch_worth_of_urls_is_rejected(): void
    {
        $this->actingAs($this->admin())
            ->postJson(route('dashboard.google-indexing.submit'), [
                'urls' => array_map(fn ($i) => 'https://example.com/p' . $i, range(1, 101)),
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('urls');
    }

    /** @test */
    public function a_broken_service_account_key_is_reported_instead_of_throwing(): void
    {
        config(['google-indexing.credentials_json' => '{"type":"service_account"}']);

        $this->actingAs($this->admin())
            ->postJson(route('dashboard.google-indexing.test'))
            ->assertStatus(422)
            ->assertJson(['ok' => false]);
    }
}
