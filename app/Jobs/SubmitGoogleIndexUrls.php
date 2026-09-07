<?php

namespace App\Jobs;

use App\Services\GoogleIndexingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Pushes a list of URLs to the Google Indexing API in the background.
 */
class SubmitGoogleIndexUrls implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 1;

    public int $timeout = 600;

    /**
     * @param  string[]  $urls
     */
    public function __construct(public array $urls, public array $options = [])
    {
    }

    public function handle(GoogleIndexingService $indexing): void
    {
        if (empty($this->urls)) {
            return;
        }

        $summary = $indexing->submit($this->urls, $this->options);

        Log::info(sprintf(
            'Google Indexing job finished: %d submitted, %d succeeded, %d failed, %d skipped (batch %s).',
            $summary['submitted'],
            $summary['success'],
            $summary['failed'],
            $summary['skipped'],
            $summary['batch_id']
        ));
    }

    public function failed(\Throwable $e): void
    {
        Log::error('Google Indexing job failed: ' . $e->getMessage());
    }
}
