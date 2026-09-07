<?php

namespace App\Console\Commands;

use App\Jobs\SubmitGoogleIndexUrls;
use App\Services\GoogleIndexingService;
use App\Services\IndexableUrlCollector;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SubmitGoogleIndexUrlsCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'google:index
        {--source=both : Where to read URLs from - sitemap, database, both or manual}
        {--url=* : Submit these URLs instead of collecting them}
        {--type=URL_UPDATED : URL_UPDATED or URL_DELETED}
        {--since= : Only include records updated in the last N days (database source)}
        {--limit= : Maximum number of URLs to submit in this run}
        {--force : Ignore the "already submitted" window}
        {--dry-run : Show what would be sent without calling Google}
        {--queue : Dispatch the submission to the queue instead of running it inline}';

    /**
     * @var string
     */
    protected $description = 'Bulk submit URLs to the Google Indexing API';

    public function handle(GoogleIndexingService $indexing, IndexableUrlCollector $collector): int
    {
        $type = strtoupper((string) $this->option('type'));

        if (! in_array($type, [GoogleIndexingService::TYPE_UPDATED, GoogleIndexingService::TYPE_DELETED], true)) {
            $this->error('--type must be URL_UPDATED or URL_DELETED.');

            return self::FAILURE;
        }

        if (! $this->option('dry-run') && ! $indexing->hasCredentials()) {
            $this->error('No service account key found. Upload one in the dashboard or set GOOGLE_INDEXING_CREDENTIALS.');

            return self::FAILURE;
        }

        $source = (string) $this->option('source');
        $urls = (array) $this->option('url');

        if (empty($urls)) {
            $since = $this->option('since') ? Carbon::now()->subDays((int) $this->option('since')) : null;

            $this->line('Collecting URLs from: ' . $source . ($since ? ' (updated since ' . $since->toDateString() . ')' : ''));
            $urls = $collector->collect($source, $since);
            $source = $source === 'manual' ? 'manual' : 'command:' . $source;
        } else {
            $source = 'command:manual';
        }

        if (empty($urls)) {
            $this->warn('No URLs to submit.');

            return self::SUCCESS;
        }

        $this->info(count($urls) . ' URL(s) collected. Quota left today: ' . $indexing->remainingQuotaToday() . '/' . $indexing->dailyQuota());

        $options = [
            'type'    => $type,
            'source'  => $source,
            'force'   => (bool) $this->option('force'),
            'dry_run' => (bool) $this->option('dry-run'),
            'limit'   => $this->option('limit') ? (int) $this->option('limit') : null,
        ];

        if ($this->option('queue') && ! $this->option('dry-run')) {
            SubmitGoogleIndexUrls::dispatch($urls, $options);
            $this->info('Submission dispatched to the queue.');

            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar(count($urls));
        $bar->start();

        $options['progress'] = function (array $results) use ($bar) {
            $bar->advance(count($results));
        };

        $summary = $indexing->submit($urls, $options);

        $bar->finish();
        $this->newLine(2);

        if (! empty($summary['dry_run'])) {
            $this->warn('Dry run - nothing was sent to Google.');
            $this->line(count($summary['pending']) . ' URL(s) would have been submitted:');

            foreach (array_slice($summary['pending'], 0, 20) as $url) {
                $this->line('  - ' . $url);
            }

            if (count($summary['pending']) > 20) {
                $this->line('  ... and ' . (count($summary['pending']) - 20) . ' more.');
            }

            return self::SUCCESS;
        }

        $this->table(['Submitted', 'Success', 'Failed', 'Skipped', 'Quota left'], [[
            $summary['submitted'],
            $summary['success'],
            $summary['failed'],
            $summary['skipped'],
            $summary['quota_left'] . '/' . $indexing->dailyQuota(),
        ]]);

        foreach ($summary['errors'] as $error) {
            $this->warn($error);
        }

        $failures = array_filter($summary['results'], fn ($result) => ($result['status'] ?? null) === 'failed');

        foreach (array_slice($failures, 0, 10) as $failure) {
            $this->error($failure['url'] . ' - ' . ($failure['message'] ?? 'unknown error'));
        }

        return $summary['failed'] > 0 && $summary['success'] === 0 ? self::FAILURE : self::SUCCESS;
    }
}
