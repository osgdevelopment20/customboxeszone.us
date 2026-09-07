<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Symfony\Component\Process\Process;

/**
 * Pulls the latest code onto the server.
 *
 * The deploy never relies on the server checkout having a configured "origin"
 * remote: the repository URL comes from config and is passed to "git fetch"
 * directly. Credentials are handed to git through a credential helper that
 * reads environment variables, so the token never appears in the process
 * arguments (visible in "ps" on shared hosting) or in an exception message.
 */
class DeployController extends Controller
{
    /**
     * API entry point - authorised with the shared deploy secret.
     */
    public function pull(Request $request): JsonResponse
    {
        if (! $this->isAuthorized($request)) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }

        return $this->performPull();
    }

    /**
     * Dashboard entry point - authorised by the "auth" + "can:pull-code"
     * middleware on the route, so no URL token is involved.
     */
    public function pullFromDashboard(): JsonResponse
    {
        return $this->performPull();
    }

    /**
     * Report what the deploy would actually do, without changing anything.
     * Use this to diagnose a failing deploy.
     */
    public function status(Request $request): JsonResponse
    {
        if (! $this->isAuthorized($request)) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }

        return response()->json($this->diagnostics());
    }

    /* -----------------------------------------------------------------
     |  Deploy
     | -----------------------------------------------------------------
     */

    private function performPull(): JsonResponse
    {
        $branch = (string) config('deploy.branch', 'main');

        try {
            $this->assertUsableRepository();

            $url = $this->repositoryUrl();
            $before = $this->currentHead();

            $this->git(array_merge($this->credentialConfig(), ['fetch', '--no-tags', '--force', $url, $branch]));
            $this->git(['reset', '--hard', 'FETCH_HEAD']);

            $after = $this->currentHead();
            $caches = $this->refreshCaches();

            Log::info('Deploy pull succeeded.', ['branch' => $branch, 'from' => $before, 'to' => $after]);

            return response()->json([
                'status'   => 'success',
                'branch'   => $branch,
                'previous' => $before,
                'current'  => $after,
                'changed'  => $before !== $after,
                'caches'   => $caches,
            ]);
        } catch (\Throwable $e) {
            $message = $this->redact($e->getMessage());

            Log::error('Deploy pull failed.', ['error' => $message]);

            return response()->json([
                'status'      => 'error',
                'message'     => $message,
                'diagnostics' => $this->diagnostics(),
            ], 500);
        }
    }

    /**
     * Refuse to run when the checkout is not usable, and - importantly - when
     * the repository root is not the application directory. A "git reset
     * --hard" inside a parent repository would rewrite the wrong tree.
     */
    private function assertUsableRepository(): void
    {
        $version = $this->git(['--version'], false);

        if ($version['code'] !== 0) {
            throw new RuntimeException(
                'git is not available to PHP on this server. '
                . ($version['error'] ?: 'Running "git --version" returned exit code ' . $version['code'] . '.')
                . ' Ask the host to enable the git binary, or remove it from disable_functions.'
            );
        }

        $top = $this->git(['rev-parse', '--show-toplevel'], false);

        if ($top['code'] !== 0) {
            throw new RuntimeException(
                base_path() . ' is not a git repository (' . ($top['error'] ?: 'exit code ' . $top['code']) . '). '
                . 'The site was most likely uploaded by FTP or from a zip. Clone the repository on the server, '
                . 'or run "git init" there and add the remote, before using the deploy endpoint.'
            );
        }

        $root = realpath($top['output']);
        $base = realpath(base_path());

        if ($root === false || $base === false || $root !== $base) {
            throw new RuntimeException(sprintf(
                'Refusing to deploy: the git repository root is "%s" but the application lives in "%s". '
                . 'A "git reset --hard" would rewrite the wrong directory.',
                $top['output'],
                base_path()
            ));
        }
    }

    /**
     * Where to fetch from. Configuration wins so the deploy keeps working on a
     * checkout that has no remotes; the "origin" URL is only a fallback.
     */
    private function repositoryUrl(): string
    {
        $configured = trim((string) config('deploy.repository'));

        if ($configured !== '') {
            return preg_match('#^(https?|git|ssh)://#i', $configured) || str_starts_with($configured, 'git@')
                ? $configured
                : 'https://github.com/' . trim($configured, '/') . '.git';
        }

        $remote = (string) config('deploy.remote', 'origin');
        $result = $this->git(['config', '--get', "remote.{$remote}.url"], false);

        if ($result['code'] === 0 && $result['output'] !== '') {
            return $result['output'];
        }

        throw new RuntimeException(sprintf(
            'No repository URL available: DEPLOY_GITHUB_REPOSITORY is empty and the "%s" remote is not configured '
            . 'in %s. Set DEPLOY_GITHUB_REPOSITORY=owner/repo in the server .env, or run '
            . '"git remote add %s <url>" in that directory.',
            $remote,
            base_path(),
            $remote
        ));
    }

    /**
     * Feed the token to git through a credential helper that reads the
     * environment, instead of embedding it in the URL.
     *
     * @return array<int, string>
     */
    private function credentialConfig(): array
    {
        if ($this->credentialsConfigured()) {
            return [
                // Clear any inherited helper first so only ours is consulted.
                '-c', 'credential.helper=',
                '-c', 'credential.helper=!f() { echo "username=$DEPLOY_GIT_USER"; echo "password=$DEPLOY_GIT_PASS"; }; f',
            ];
        }

        return [];
    }

    private function credentialsConfigured(): bool
    {
        return (string) config('deploy.github_username') !== ''
            && (string) config('deploy.github_token') !== '';
    }

    /**
     * New code needs the compiled config/routes/views to be rebuilt, otherwise
     * the server keeps serving the previous release from cache.
     *
     * @return array<int, string>
     */
    private function refreshCaches(): array
    {
        if (! config('deploy.clear_cache', true)) {
            return ['skipped (deploy.clear_cache is false)'];
        }

        $cleared = [];

        foreach (['config:clear', 'route:clear', 'view:clear'] as $command) {
            try {
                Artisan::call($command);
                $cleared[] = $command;
            } catch (\Throwable $e) {
                $cleared[] = $command . ' failed: ' . $this->redact($e->getMessage());
            }
        }

        if (app()->bound('responsecache')) {
            try {
                app('responsecache')->clear();
                $cleared[] = 'response cache';
            } catch (\Throwable $e) {
                $cleared[] = 'response cache failed: ' . $this->redact($e->getMessage());
            }
        }

        return $cleared;
    }

    private function currentHead(): ?string
    {
        $result = $this->git(['rev-parse', '--short', 'HEAD'], false);

        return $result['code'] === 0 ? ($result['output'] ?: null) : null;
    }

    /* -----------------------------------------------------------------
     |  Diagnostics
     | -----------------------------------------------------------------
     */

    /**
     * A snapshot of everything the deploy depends on, with secrets removed.
     *
     * @return array<string, mixed>
     */
    private function diagnostics(): array
    {
        $version = $this->git(['--version'], false);
        $top = $this->git(['rev-parse', '--show-toplevel'], false);
        $remotes = $this->git(['remote', '-v'], false);
        $head = $this->git(['rev-parse', '--short', 'HEAD'], false);
        $branch = $this->git(['rev-parse', '--abbrev-ref', 'HEAD'], false);

        $repository = trim((string) config('deploy.repository'));

        return [
            'base_path'              => base_path(),
            'git_available'          => $version['code'] === 0,
            'git_version'            => $version['code'] === 0 ? $version['output'] : $this->redact($version['error']),
            'is_git_repository'      => $top['code'] === 0,
            'repository_root'        => $top['code'] === 0 ? $top['output'] : $this->redact($top['error']),
            'repository_root_matches' => $top['code'] === 0 && realpath($top['output']) === realpath(base_path()),
            'current_head'           => $head['code'] === 0 ? $head['output'] : null,
            'current_branch'         => $branch['code'] === 0 ? $branch['output'] : null,
            'configured_branch'      => (string) config('deploy.branch', 'main'),
            'configured_repository'  => $repository !== '' ? $this->redact($repository) : null,
            'configured_remotes'     => $remotes['code'] === 0 && $remotes['output'] !== ''
                ? array_values(array_filter(array_map(
                    fn ($line) => $this->redact(trim($line)),
                    explode("\n", $remotes['output'])
                )))
                : [],
            'credentials_configured' => $this->credentialsConfigured(),
            'secret_configured'      => (string) config('deploy.secret') !== '',
        ];
    }

    /* -----------------------------------------------------------------
     |  Helpers
     | -----------------------------------------------------------------
     */

    /**
     * Verify the request carries the configured deploy secret.
     */
    private function isAuthorized(Request $request): bool
    {
        $configured = (string) config('deploy.secret');

        if ($configured === '') {
            return false;
        }

        $provided = (string) ($request->header('X-Deploy-Secret') ?? $request->input('secret', ''));

        return $provided !== '' && hash_equals($configured, $provided);
    }

    /**
     * Run a git command in the application directory.
     *
     * The token is exported to the process environment (readable only by the
     * same user) rather than passed as an argument, and the thrown message
     * repeats only the plain arguments - never the credential helper.
     *
     * @param  array<int, string>  $args
     * @return array{code: int, output: string, error: string}
     */
    private function git(array $args, bool $mustSucceed = true): array
    {
        $process = new Process(array_merge(['git'], $args), base_path(), [
            'GIT_TERMINAL_PROMPT' => '0',
            'DEPLOY_GIT_USER'     => (string) config('deploy.github_username'),
            'DEPLOY_GIT_PASS'     => (string) config('deploy.github_token'),
        ]);

        $process->setTimeout((int) config('deploy.timeout', 300));
        $process->run();

        $result = [
            'code'   => (int) $process->getExitCode(),
            'output' => trim($process->getOutput()),
            'error'  => trim($process->getErrorOutput()),
        ];

        if ($mustSucceed && $result['code'] !== 0) {
            throw new RuntimeException(sprintf(
                'git %s failed (exit code %d): %s',
                implode(' ', $this->withoutCredentialConfig($args)),
                $result['code'],
                $result['error'] ?: $result['output'] ?: 'no output'
            ));
        }

        return $result;
    }

    /**
     * Drop the "-c credential.helper=..." pairs so they never reach a message.
     *
     * @param  array<int, string>  $args
     * @return array<int, string>
     */
    private function withoutCredentialConfig(array $args): array
    {
        $clean = [];

        for ($i = 0; $i < count($args); $i++) {
            if ($args[$i] === '-c') {
                $i++; // skip the value that follows

                continue;
            }

            $clean[] = $args[$i];
        }

        return $clean;
    }

    /**
     * Strip anything that looks like a credential out of a string.
     */
    private function redact(string $message): string
    {
        $token = (string) config('deploy.github_token');

        if ($token !== '') {
            $message = str_replace($token, '***', $message);
        }

        $message = (string) preg_replace('#(https?://)[^/@\s]+:[^/@\s]+@#i', '$1***:***@', $message);

        return (string) preg_replace('#gh[pousr]_[A-Za-z0-9]{10,}|github_pat_[A-Za-z0-9_]{10,}#', '***', $message);
    }
}
