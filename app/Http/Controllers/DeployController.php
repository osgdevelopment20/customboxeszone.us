<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class DeployController extends Controller
{
    /**
     * Pull the latest code from GitHub via "git pull".
     */
    public function pull(Request $request): JsonResponse
    {
        if (! $this->isAuthorized($request)) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }

        $remote = (string) config('deploy.remote', 'origin');
        $branch = (string) config('deploy.branch', 'main');
        $username = (string) config('deploy.github_username');
        $token = (string) config('deploy.github_token');

        try {
            $repoUrl = $this->authenticatedRemoteUrl($remote, $username, $token);

            $output = $this->runGit(['fetch', $repoUrl, $branch]);
            $output .= $this->runGit(['reset', '--hard', 'FETCH_HEAD']);

            Log::info('Deploy pull succeeded.', ['branch' => $branch]);

            return response()->json([
                'status' => 'success',
                'branch' => $branch,
                'output' => trim($output),
            ]);
        } catch (ProcessFailedException $e) {
            Log::error('Deploy pull failed.', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => 'git pull failed. Check the application logs for details.',
            ], 500);
        }
    }

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
     * Build an authenticated HTTPS URL for the remote if credentials exist,
     * otherwise fall back to the configured remote name.
     */
    private function authenticatedRemoteUrl(string $remote, string $username, string $token): string
    {
        if ($username === '' || $token === '') {
            return $remote;
        }

        $current = trim($this->runGit(['config', '--get', "remote.{$remote}.url"]));

        if (str_starts_with($current, 'https://')) {
            $host = substr($current, strlen('https://'));

            // Strip any credentials already embedded in the URL (user:pass@host).
            if (($atPos = strpos($host, '@')) !== false) {
                $host = substr($host, $atPos + 1);
            }

            return sprintf('https://%s:%s@%s', rawurlencode($username), rawurlencode($token), $host);
        }

        return $remote;
    }

    /**
     * Run a git command in the application base path and return its output.
     *
     * @param  array<int, string>  $args
     */
    private function runGit(array $args): string
    {
        $process = new Process(array_merge(['git'], $args), base_path());
        $process->setTimeout(120);
        $process->mustRun();

        return $process->getOutput().$process->getErrorOutput();
    }
}
