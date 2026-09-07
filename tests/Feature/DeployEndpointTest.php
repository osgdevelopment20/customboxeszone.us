<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DeployEndpointTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'deploy.secret'          => 'test-deploy-secret',
            'deploy.github_token'    => 'ghp_pretendtokenvalue1234567890',
            'deploy.github_username' => 'deploy-bot',
        ]);
    }

    /** @test */
    public function the_api_endpoint_rejects_a_request_without_the_secret(): void
    {
        $this->postJson('/api/deploy/pull')->assertStatus(401);
        $this->postJson('/api/deploy/pull', ['secret' => 'wrong'])->assertStatus(401);
    }

    /** @test */
    public function the_api_endpoint_is_disabled_when_no_secret_is_configured(): void
    {
        config(['deploy.secret' => '']);

        $this->postJson('/api/deploy/pull', ['secret' => ''])->assertStatus(401);
    }

    /** @test */
    public function the_status_endpoint_reports_the_repository_state_without_leaking_the_token(): void
    {
        $response = $this->withHeader('X-Deploy-Secret', 'test-deploy-secret')
            ->postJson('/api/deploy/status')
            ->assertOk()
            ->assertJsonStructure([
                'base_path',
                'git_available',
                'is_git_repository',
                'repository_root',
                'repository_root_matches',
                'current_head',
                'current_branch',
                'configured_branch',
                'configured_remotes',
                'credentials_configured',
                'secret_configured',
            ]);

        $this->assertStringNotContainsString('ghp_pretendtokenvalue1234567890', $response->getContent());
    }

    /** @test */
    public function the_dashboard_pull_route_requires_a_logged_in_user(): void
    {
        // Previously this route compared a query token against env(), which is
        // null once the config is cached - so an unauthenticated request passed.
        $this->get('/git-pull')->assertRedirect(route('login'));
        $this->get('/git-pull?token=')->assertRedirect(route('login'));
    }

    /** @test */
    public function the_dashboard_pull_route_requires_the_pull_code_permission(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/git-pull')
            ->assertForbidden();
    }
}
