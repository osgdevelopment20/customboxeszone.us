@extends('backend.layouts.app')

@section('title', 'Google Search Indexing')

@section('contents')
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <p class="text-uppercase fw-medium text-muted mb-3">Quota used today</p>
                    <h4 class="fs-22 fw-semibold mb-1">{{ $usedToday }} <small class="text-muted fs-13">/ {{ $dailyQuota }}</small></h4>
                    <div class="progress progress-sm mt-3">
                        <div class="progress-bar bg-primary" style="width: {{ $dailyQuota > 0 ? min(100, round($usedToday / $dailyQuota * 100)) : 0 }}%"></div>
                    </div>
                    <p class="text-muted mt-2 mb-0">{{ $remaining }} URLs left today</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <p class="text-uppercase fw-medium text-muted mb-3">Indexed successfully</p>
                    <h4 class="fs-22 fw-semibold mb-1 text-success">{{ number_format($stats['success']) }}</h4>
                    <p class="text-muted mb-0">{{ number_format($stats['this_month']) }} sent this month</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <p class="text-uppercase fw-medium text-muted mb-3">Failed</p>
                    <h4 class="fs-22 fw-semibold mb-1 text-danger">{{ number_format($stats['failed']) }}</h4>
                    <p class="text-muted mb-0">out of {{ number_format($stats['total']) }} requests</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <p class="text-uppercase fw-medium text-muted mb-3">Connection</p>
                    @if ($hasCredentials)
                        <h4 class="fs-22 fw-semibold mb-1 text-success">Configured</h4>
                        <p class="text-muted text-truncate mb-0" title="{{ $serviceEmail }}">{{ $serviceEmail }}</p>
                    @else
                        <h4 class="fs-22 fw-semibold mb-1 text-warning">Not set up</h4>
                        <p class="text-muted mb-0">Upload a service account key</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-7">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h4 class="card-title mb-0 flex-grow-1">Bulk submit URLs</h4>
                    <span class="badge bg-primary-subtle text-primary">{{ $batchSize }} URLs per batch request</span>
                </div>
                <div class="card-body">
                    @unless ($hasCredentials)
                        <div class="alert alert-warning">
                            Add the service account key below before submitting URLs.
                        </div>
                    @endunless

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="gi-source">URL source</label>
                            <select id="gi-source" class="form-select">
                                <option value="both">Sitemap + database</option>
                                <option value="database">Database (products, categories, blogs)</option>
                                <option value="sitemap">Sitemap files</option>
                                <option value="manual">Paste URLs manually</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="gi-type">Notification type</label>
                            <select id="gi-type" class="form-select">
                                <option value="URL_UPDATED">URL_UPDATED (crawl / re-crawl)</option>
                                <option value="URL_DELETED">URL_DELETED (page removed)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="gi-since">Changed in last (days)</label>
                            <input type="number" id="gi-since" class="form-control" min="0" placeholder="all" />
                        </div>

                        <div class="col-12 d-none" id="gi-manual-wrap">
                            <label class="form-label" for="gi-urls">URLs, one per line</label>
                            <textarea id="gi-urls" class="form-control" rows="6" placeholder="{{ url('/') }}/example-page"></textarea>
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="gi-force">
                                <label class="form-check-label" for="gi-force">
                                    Resend URLs that were already submitted in the last {{ $resubmitDays }} day(s)
                                </label>
                            </div>
                        </div>

                        <div class="col-12">
                            <button type="button" class="btn btn-soft-primary" id="gi-load">
                                <i class="ri-search-line align-bottom me-1"></i> Load URLs
                            </button>
                            <button type="button" class="btn btn-primary d-none" id="gi-submit">
                                <i class="ri-send-plane-line align-bottom me-1"></i> Submit to Google
                            </button>
                            <button type="button" class="btn btn-soft-secondary" id="gi-test">
                                <i class="ri-plug-line align-bottom me-1"></i> Test connection
                            </button>
                        </div>
                    </div>

                    <div id="gi-preview" class="mt-3 d-none">
                        <div class="alert alert-info mb-2" id="gi-preview-text"></div>
                        <div class="border rounded p-2" style="max-height: 220px; overflow-y: auto;">
                            <ul class="list-unstyled mb-0 small text-muted" id="gi-preview-list"></ul>
                        </div>
                    </div>

                    <div id="gi-progress-wrap" class="mt-3 d-none">
                        <div class="progress" style="height: 12px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" id="gi-progress" style="width: 0%"></div>
                        </div>
                        <p class="text-muted mt-2 mb-0" id="gi-progress-text"></p>
                    </div>

                    <div id="gi-result" class="mt-3"></div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Check a URL with Google</h4>
                </div>
                <div class="card-body">
                    <div class="input-group">
                        <input type="url" class="form-control" id="gi-check-url" placeholder="{{ url('/') }}/example-page">
                        <button class="btn btn-soft-primary" type="button" id="gi-check">Check status</button>
                    </div>
                    <pre class="mt-3 mb-0 d-none bg-light p-2 rounded small" id="gi-check-result"></pre>
                </div>
            </div>
        </div>

        <div class="col-xl-5">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Service account</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('dashboard.google-indexing.settings') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="credentials">Service account JSON key</label>
                            <input type="file" name="credentials" id="credentials" class="form-control" accept="application/json,.json">
                            <div class="form-text">
                                Create a service account in the
                                <a href="https://console.cloud.google.com/apis/library/indexing.googleapis.com" target="_blank" rel="noopener">Google Cloud Console</a>,
                                enable the Indexing API, download the JSON key and add
                                @if ($serviceEmail)
                                    <code>{{ $serviceEmail }}</code>
                                @else
                                    the service account e-mail
                                @endif
                                as an <strong>Owner</strong> of the property in
                                <a href="https://search.google.com/search-console/users" target="_blank" rel="noopener">Search Console</a>.
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="daily_quota">Daily quota</label>
                                <input type="number" name="daily_quota" id="daily_quota" class="form-control" value="{{ $dailyQuota }}" min="1">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="batch_size">Batch size</label>
                                <input type="number" name="batch_size" id="batch_size" class="form-control" value="{{ $batchSize }}" min="1" max="100">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="resubmit_after_days">Resubmit after (days)</label>
                                <input type="number" name="resubmit_after_days" id="resubmit_after_days" class="form-control" value="{{ $resubmitDays }}" min="0">
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Save settings</button>
                            @if ($hasCredentials)
                                <a href="{{ route('dashboard.google-indexing.credentials.delete') }}"
                                   class="btn btn-soft-danger"
                                   onclick="return confirm('Remove the stored service account key?')">Remove key</a>
                            @endif
                        </div>
                    </form>

                    <hr>

                    <h6 class="text-muted text-uppercase fs-12">Run it from the CLI / cron</h6>
                    <pre class="bg-light p-2 rounded small mb-0">php artisan google:index --source=database --since=2 --limit=200</pre>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h4 class="card-title mb-0 flex-grow-1">Submission log</h4>
            <form action="{{ route('dashboard.google-indexing.index') }}" method="GET" class="d-flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Search URL">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="success" @selected(request('status') === 'success')>Success</option>
                    <option value="failed" @selected(request('status') === 'failed')>Failed</option>
                </select>
                <button class="btn btn-sm btn-soft-primary" type="submit">Filter</button>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm align-middle table-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>URL</th>
                            <th>Type</th>
                            <th>Source</th>
                            <th>Status</th>
                            <th>Notified</th>
                            <th>Submitted</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr>
                                <td class="text-truncate" style="max-width: 380px;">
                                    <a href="{{ $log->url }}" target="_blank" rel="noopener">{{ $log->url }}</a>
                                </td>
                                <td><span class="badge bg-light text-body">{{ $log->type }}</span></td>
                                <td class="text-muted">{{ $log->source }}</td>
                                <td>
                                    <span class="badge bg-{{ $log->status_badge }}">{{ $log->status }}</span>
                                    @if ($log->message)
                                        <i class="ri-information-line text-muted" title="{{ $log->message }}"></i>
                                    @endif
                                </td>
                                <td class="text-muted">{{ optional($log->notify_time)->format('d M Y H:i') ?? '-' }}</td>
                                <td class="text-muted">{{ optional($log->submitted_at)->format('d M Y H:i') ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Nothing submitted yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3 d-flex align-items-center justify-content-between">
                {{ $logs->links() }}
                <form action="{{ route('dashboard.google-indexing.clear-logs') }}" method="POST"
                      onsubmit="return confirm('Delete log entries older than today?')">
                    @csrf
                    <button class="btn btn-sm btn-soft-danger" type="submit">Clear old logs</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
(function () {
    const routes = {
        collect: @json(route('dashboard.google-indexing.collect')),
        submit:  @json(route('dashboard.google-indexing.submit')),
        status:  @json(route('dashboard.google-indexing.status')),
        test:    @json(route('dashboard.google-indexing.test')),
    };

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const chunkSize = {{ $batchSize }};

    const el = (id) => document.getElementById(id);
    const source = el('gi-source');
    const preview = el('gi-preview');
    const previewText = el('gi-preview-text');
    const previewList = el('gi-preview-list');
    const submitBtn = el('gi-submit');
    const loadBtn = el('gi-load');
    const result = el('gi-result');
    const progressWrap = el('gi-progress-wrap');
    const progress = el('gi-progress');
    const progressText = el('gi-progress-text');

    let pending = [];

    const post = async (url, payload) => {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify(payload || {}),
        });

        const data = await response.json().catch(() => ({}));

        if (!response.ok) {
            throw new Error(data.message || ('Request failed with status ' + response.status));
        }

        return data;
    };

    const alertBox = (type, html) => `<div class="alert alert-${type} mb-2">${html}</div>`;

    source.addEventListener('change', () => {
        el('gi-manual-wrap').classList.toggle('d-none', source.value !== 'manual');
        el('gi-since').disabled = source.value !== 'database' && source.value !== 'both';
    });

    el('gi-test').addEventListener('click', async () => {
        result.innerHTML = alertBox('info', 'Contacting Google...');

        try {
            const data = await post(routes.test);
            result.innerHTML = alertBox('success', `Connection OK - authenticated as <code>${data.email}</code> (project <code>${data.project || 'n/a'}</code>).`);
        } catch (e) {
            result.innerHTML = alertBox('danger', e.message);
        }
    });

    loadBtn.addEventListener('click', async () => {
        loadBtn.disabled = true;
        result.innerHTML = '';
        preview.classList.add('d-none');
        submitBtn.classList.add('d-none');

        try {
            const data = await post(routes.collect, {
                source: source.value,
                since: el('gi-since').value || null,
                urls: el('gi-urls').value,
                type: el('gi-type').value,
                force: el('gi-force').checked,
            });

            pending = data.pending || [];

            const invalidCount = Object.keys(data.invalid || {}).length;
            previewText.innerHTML = `<strong>${pending.length}</strong> URL(s) ready to submit `
                + `&middot; ${data.skipped} skipped (recently submitted) `
                + `&middot; ${invalidCount} invalid `
                + `&middot; quota left today: <strong>${data.quota_left}</strong>`;

            previewList.innerHTML = pending.slice(0, 200)
                .map((url) => `<li class="text-truncate">${url}</li>`).join('')
                + (pending.length > 200 ? `<li class="fst-italic">... and ${pending.length - 200} more</li>` : '');

            preview.classList.remove('d-none');
            submitBtn.classList.toggle('d-none', pending.length === 0);
        } catch (e) {
            result.innerHTML = alertBox('danger', e.message);
        } finally {
            loadBtn.disabled = false;
        }
    });

    submitBtn.addEventListener('click', async () => {
        if (!pending.length || !confirm(`Submit ${pending.length} URL(s) to the Google Indexing API?`)) {
            return;
        }

        submitBtn.disabled = true;
        loadBtn.disabled = true;
        result.innerHTML = '';
        progressWrap.classList.remove('d-none');

        const batchId = 'ui-' + Date.now().toString(36);
        const totals = { submitted: 0, success: 0, failed: 0, skipped: 0 };
        const failures = [];
        let done = 0;

        for (let i = 0; i < pending.length; i += chunkSize) {
            const chunk = pending.slice(i, i + chunkSize);

            try {
                const data = await post(routes.submit, {
                    urls: chunk,
                    type: el('gi-type').value,
                    source: source.value,
                    force: el('gi-force').checked,
                    batch_id: batchId,
                });

                totals.submitted += data.submitted;
                totals.success += data.success;
                totals.failed += data.failed;
                totals.skipped += data.skipped;

                (data.results || []).filter((r) => r.status === 'failed')
                    .forEach((r) => failures.push(`${r.url} - ${r.message || 'unknown error'}`));

                if (data.quota_left === 0) {
                    failures.push('Daily quota reached - remaining URLs were not sent.');
                    done = pending.length;
                    break;
                }
            } catch (e) {
                failures.push(e.message);
                break;
            }

            done = Math.min(pending.length, i + chunk.length);
            progress.style.width = Math.round((done / pending.length) * 100) + '%';
            progressText.textContent = `${done} / ${pending.length} processed`;
        }

        progress.style.width = '100%';
        progressText.textContent = `${done} / ${pending.length} processed`;

        result.innerHTML = alertBox(
            totals.failed ? 'warning' : 'success',
            `Finished: <strong>${totals.success}</strong> accepted, <strong>${totals.failed}</strong> failed, <strong>${totals.skipped}</strong> skipped.`
        ) + (failures.length
            ? alertBox('danger', '<strong>Issues</strong><br>' + failures.slice(0, 15).join('<br>'))
            : '');

        submitBtn.disabled = false;
        loadBtn.disabled = false;
    });

    el('gi-check').addEventListener('click', async () => {
        const box = el('gi-check-result');
        box.classList.remove('d-none');
        box.textContent = 'Checking...';

        try {
            const data = await post(routes.status, { url: el('gi-check-url').value });
            box.textContent = JSON.stringify(data.body, null, 2);
        } catch (e) {
            box.textContent = e.message;
        }
    });
})();
</script>
@endpush
