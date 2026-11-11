@extends('backend.layouts.app')

@section('title', 'Google Reviews Settings')

@section('contents')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Google Reviews Settings</h4>
            </div>
            <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form action="{{ route('dashboard.google-reviews.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="api_key" class="form-label">Google API Key</label>
                            <input type="text" class="form-control @error('api_key') is-invalid @enderror" 
                                   id="api_key" name="api_key" 
                                   value="{{ old('api_key', $settings->api_key) }}" 
                                   placeholder="Enter your Google API Key">
                            @error('api_key')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Get your API key from <a href="https://console.cloud.google.com/" target="_blank">Google Cloud Console</a>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="place_id" class="form-label">Google Place ID</label>
                            <input type="text" class="form-control @error('place_id') is-invalid @enderror" 
                                   id="place_id" name="place_id" 
                                   value="{{ old('place_id', $settings->place_id) }}" 
                                   placeholder="Enter your Google Place ID">
                            @error('place_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Find your Place ID using <a href="https://developers.google.com/maps/documentation/places/web-service/place-id" target="_blank">Google Place ID Finder</a>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                                       value="1" {{ old('is_active', $settings->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Enable Google Reviews
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Settings
                        </button>
                    </form>
                </div>
            </div>
        </div>

    <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Actions</h4>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-success" id="fetchReviewsBtn">
                            <i class="fas fa-sync"></i> Fetch Reviews Now
                        </button>
                        
                        <a href="{{ route('dashboard.google-reviews.manual-import') }}" class="btn btn-info">
                            <i class="fas fa-upload"></i> Manual Import
                        </a>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h4 class="card-title">Recent Reviews</h4>
                </div>
                <div class="card-body">
                    @php
                        $recentReviews = \App\Models\GoogleReview::latest()->take(5)->get();
                    @endphp
                    
                    @if($recentReviews->count() > 0)
                        @foreach($recentReviews as $review)
                            <div class="d-flex align-items-center mb-2">
                                <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center me-2">
                                    {{ substr($review->reviewer_name, 0, 2) }}
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold">{{ $review->reviewer_name }}</div>
                                    <div class="text-muted small">
                                        {{ \Carbon\Carbon::parse($review->review_date)->format('M d, Y') }}
                                    </div>
                                </div>
                                <div class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $review->rating ? '' : 'text-muted' }}"></i>
                                    @endfor
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No reviews found. Fetch reviews to see them here.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 12px;
    font-weight: bold;
}
</style>

<script>
document.getElementById('fetchReviewsBtn').addEventListener('click', function() {
    const btn = this;
    const originalText = btn.innerHTML;
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Fetching...';
    btn.disabled = true;
    
    fetch('{{ route("dashboard.google-reviews.fetch") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Reviews fetched successfully!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('Error fetching reviews: ' + error.message);
    })
    .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
});

function copyCronUrl() {
    const cronUrl = document.getElementById('cronUrl');
    cronUrl.select();
    cronUrl.setSelectionRange(0, 99999);
    document.execCommand('copy');
    
    // Show feedback
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-check"></i>';
    setTimeout(() => {
        btn.innerHTML = originalText;
    }, 2000);
}
</script>
@endsection
