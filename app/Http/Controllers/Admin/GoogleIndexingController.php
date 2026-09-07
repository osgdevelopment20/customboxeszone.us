<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GoogleIndexRequest;
use App\Models\Setting;
use App\Services\GoogleIndexingService;
use App\Services\IndexableUrlCollector;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class GoogleIndexingController extends Controller
{
    public function __construct(
        protected GoogleIndexingService $indexing,
        protected IndexableUrlCollector $collector
    ) {
    }

    /**
     * Settings, quota overview and the submission log.
     */
    public function index(Request $request)
    {
        $logs = GoogleIndexRequest::query()
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('search'), fn ($query) => $query->where('url', 'like', '%' . $request->search . '%'))
            ->latest('id')
            ->paginate(25)
            ->withQueryString();

        return view('admin.google-indexing.index', [
            'logs'           => $logs,
            'hasCredentials' => $this->indexing->hasCredentials(),
            'serviceEmail'   => $this->indexing->serviceAccountEmail(),
            'dailyQuota'     => $this->indexing->dailyQuota(),
            'usedToday'      => $this->indexing->usedQuotaToday(),
            'remaining'      => $this->indexing->remainingQuotaToday(),
            'resubmitDays'   => $this->indexing->resubmitAfterDays(),
            'batchSize'      => $this->indexing->batchSize(),
            'stats'          => $this->stats(),
        ]);
    }

    /**
     * Upload the service account key and store the module preferences.
     */
    public function saveSettings(Request $request)
    {
        $request->validate([
            'credentials'         => ['nullable', 'file', 'max:64'],
            'daily_quota'         => ['nullable', 'integer', 'min:1', 'max:10000'],
            'batch_size'          => ['nullable', 'integer', 'min:1', 'max:100'],
            'resubmit_after_days' => ['nullable', 'integer', 'min:0', 'max:365'],
        ]);

        if ($request->hasFile('credentials')) {
            try {
                $credentials = $this->indexing->storeCredentials(
                    (string) file_get_contents($request->file('credentials')->getRealPath())
                );
            } catch (\Throwable $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }

            $this->putSetting('google_indexing_service_email', $credentials['client_email']);
        }

        foreach (['daily_quota', 'batch_size', 'resubmit_after_days'] as $key) {
            if ($request->filled($key)) {
                $this->putSetting('google_indexing_' . $key, (int) $request->input($key));
            }
        }

        Cache::forget('sys_settings');

        return redirect()->route('dashboard.google-indexing.index')->with('success', 'Google Indexing settings saved.');
    }

    /**
     * Remove the stored service account key.
     */
    public function deleteCredentials()
    {
        $this->indexing->deleteCredentials();

        return redirect()->back()->with('success', 'Service account key removed.');
    }

    /**
     * Verify that the key can actually obtain an access token.
     */
    public function testConnection(): JsonResponse
    {
        try {
            return response()->json($this->indexing->testConnection());
        } catch (\Throwable $e) {
            return response()->json(['ok' => false, 'message' => $e->getMessage()], 422);
        }
    }

    /**
     * Build the URL list for the chosen source without submitting anything.
     */
    public function collect(Request $request): JsonResponse
    {
        $request->validate([
            'source' => ['required', 'in:sitemap,database,both,manual'],
            'since'  => ['nullable', 'integer', 'min:0', 'max:3650'],
            'urls'   => ['nullable', 'string'],
        ]);

        $source = $request->input('source');

        if ($source === 'manual') {
            $urls = $this->collector->fromText($request->input('urls'));
        } else {
            $since = $request->filled('since') && (int) $request->input('since') > 0
                ? Carbon::now()->subDays((int) $request->input('since'))
                : null;

            $urls = $this->collector->collect($source, $since);
        }

        [$valid, $invalid] = $this->indexing->partitionValidUrls($urls);

        $recent = $request->boolean('force')
            ? []
            : $this->indexing->recentlySubmitted($valid, $request->input('type', GoogleIndexingService::TYPE_UPDATED));

        $pending = array_values(array_diff($valid, array_keys($recent)));

        return response()->json([
            'ok'         => true,
            'total'      => count($valid) + count($invalid),
            'pending'    => $pending,
            'skipped'    => count($recent),
            'invalid'    => $invalid,
            'quota_left' => $this->indexing->remainingQuotaToday(),
        ]);
    }

    /**
     * Submit one chunk of URLs. The dashboard calls this repeatedly so a large
     * list never runs into the request timeout.
     */
    public function submit(Request $request): JsonResponse
    {
        $request->validate([
            'urls'     => ['required', 'array', 'min:1', 'max:100'],
            'urls.*'   => ['required', 'string'],
            'type'     => ['nullable', 'in:URL_UPDATED,URL_DELETED'],
            'source'   => ['nullable', 'string', 'max:40'],
            'batch_id' => ['nullable', 'string', 'max:40'],
        ]);

        try {
            $summary = $this->indexing->submit($request->input('urls'), [
                'type'     => $request->input('type', GoogleIndexingService::TYPE_UPDATED),
                'source'   => 'dashboard:' . $request->input('source', 'manual'),
                'force'    => $request->boolean('force'),
                'batch_id' => $request->input('batch_id') ?: null,
            ]);
        } catch (\Throwable $e) {
            return response()->json(['ok' => false, 'message' => $e->getMessage()], 422);
        }

        return response()->json(['ok' => true] + $summary);
    }

    /**
     * Ask Google when it last picked a URL up.
     */
    public function status(Request $request): JsonResponse
    {
        $request->validate(['url' => ['required', 'url']]);

        try {
            return response()->json($this->indexing->metadata($request->input('url')));
        } catch (\Throwable $e) {
            return response()->json(['ok' => false, 'message' => $e->getMessage()], 422);
        }
    }

    /**
     * Clear the submission log (quota counting restarts from the log, so this
     * only removes rows older than today unless "all" is requested).
     */
    public function clearLogs(Request $request)
    {
        $query = GoogleIndexRequest::query();

        if (! $request->boolean('all')) {
            $query->whereDate('created_at', '<', Carbon::today());
        }

        $deleted = $query->delete();

        return redirect()->back()->with('success', $deleted . ' log entrie(s) removed.');
    }

    protected function stats(): array
    {
        return [
            'total'      => GoogleIndexRequest::count(),
            'success'    => GoogleIndexRequest::successful()->count(),
            'failed'     => GoogleIndexRequest::failed()->count(),
            'last_run'   => optional(GoogleIndexRequest::latest('id')->first())->submitted_at,
            'this_month' => GoogleIndexRequest::billable()->where('submitted_at', '>=', Carbon::now()->startOfMonth())->count(),
        ];
    }

    protected function putSetting(string $key, $value): void
    {
        $setting = Setting::firstOrNew(['key' => $key]);
        $setting->value = $value;
        $setting->save();
    }
}
