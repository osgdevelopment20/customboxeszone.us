@extends('backend.layouts.app')

@section('title', 'Manual Import Google Reviews')

@section('contents')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Manual Import Google Reviews</h4>
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

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <!-- JSON File Upload Form -->
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Upload JSON File</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('dashboard.google-reviews.manual-import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="json_file" class="form-label">Select JSON File</label>
                                        <input type="file" class="form-control @error('json_file') is-invalid @enderror" 
                                               id="json_file" name="json_file" accept=".json" required>
                                        @error('json_file')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">
                                            Upload a JSON file containing OrganizationScrapedReviews array (supports both single object and array of objects format)
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="overwrite_existing" name="overwrite_existing" value="1">
                                            <label class="form-check-label" for="overwrite_existing">
                                                Overwrite existing reviews (by ReviewId)
                                            </label>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary" id="importBtn">
                                        <i class="fas fa-upload"></i> Import Reviews
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">JSON Format Example</h5>
                            </div>
                            <div class="card-body">
                                <pre class="bg-light p-3 rounded"><code>[
  {
    "OrganizationScrapedReviews": [
      {
        "ReviewId": "ChZDSUhNMG9nS0VJQ0FnSUREek92RE9BEAE",
        "ReviewerName": "John Doe",
        "ReviewerProfileLink": "",
        "ReviewerReviewsCount": "15",
        "ReviewDate": "2 weeks ago",
        "ReviewRateStars": "5",
        "ReviewText": "Great service and quality products!",
        "ReviewerAvatarURL": "https://lh3.googleusercontent.com/a-/ALV-UjV97r2r-mi7XSMJfpVx8qhUDZLc99vfb4YgGvhVntJYPemju5yZ=w36-h36-p-rp-mo-br100",
        "ReviewThumbsUpValue": "2",
        "ReviewPhotos": []
      }
    ]
  }
]</code></pre>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Existing Reviews Table -->
                <div class="row mt-4">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Existing Reviews in Database</h5>
                            </div>
                            <div class="card-body">
                                @php
                                    $reviews = \App\Models\GoogleReview::latest()->paginate(10);
                                @endphp
                                
                                @if($reviews->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Reviewer Name</th>
                                                    <th>Rating</th>
                                                    <th>Review Date</th>
                                                    <th>Review Text</th>
                                                    <th>Reviews Count</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($reviews as $review)
                                                    <tr>
                                                        <td>{{ $review->id }}</td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                @if($review->reviewer_photo)
                                                                    <img src="{{ $review->reviewer_photo }}" alt="{{ $review->reviewer_name }}" 
                                                                         class="rounded-circle me-2" width="32" height="32">
                                                                @else
                                                                    <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center me-2">
                                                                        {{ substr($review->reviewer_name, 0, 2) }}
                                                                    </div>
                                                                @endif
                                                                {{ $review->reviewer_name }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="text-warning">
                                                                @for($i = 1; $i <= 5; $i++)
                                                                    <i class="fas fa-star {{ $i <= $review->rating ? '' : 'text-muted' }}"></i>
                                                                @endfor
                                                                <span class="ms-1">{{ $review->rating }}</span>
                                                            </div>
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($review->review_date)->format('M d, Y') }}</td>
                                                        <td>
                                                            <div class="text-truncate" style="max-width: 200px;" title="{{ $review->review_text }}">
                                                                {{ $review->review_text }}
                                                            </div>
                                                        </td>
                                                        <td>{{ $review->reviewer_reviews_count ?? 'N/A' }}</td>
                                                        <td>
                                                            <span class="badge {{ $review->is_active ? 'bg-success' : 'bg-danger' }}">
                                                                {{ $review->is_active ? 'Active' : 'Inactive' }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm">
                                                                <button type="button" class="btn btn-outline-primary" 
                                                                        onclick="viewReview({{ $review->id }})" title="View">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-outline-danger" 
                                                                        onclick="deleteReview({{ $review->id }})" title="Delete">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Pagination -->
                                    <div class="d-flex justify-content-between align-items-center mt-4">
                                        <div class="text-muted">
                                            Showing {{ $reviews->firstItem() ?? 0 }} to {{ $reviews->lastItem() ?? 0 }} of {{ $reviews->total() }} results
                                        </div>
                                        <div class="pagination-wrapper">
                                            {{ $reviews->appends(request()->query())->links('pagination.custom') }}
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No reviews found</h5>
                                        <p class="text-muted">Upload a JSON file to import reviews or fetch them from Google API.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Review Detail Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewModalLabel">Review Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="reviewModalBody">
                <!-- Review details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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

#importBtn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Custom Pagination Styles */
.pagination-wrapper .pagination {
    margin: 0;
    justify-content: center;
    gap: 0.25rem;
}

.pagination-wrapper .pagination .page-item {
    margin: 0;
}

.pagination-wrapper .pagination .page-link {
    color: #6c757d;
    background-color: #fff;
    border: 1px solid #e3e6f0;
    padding: 0.625rem 1rem;
    border-radius: 0.5rem;
    transition: all 0.2s ease-in-out;
    min-width: 44px;
    text-align: center;
    font-weight: 500;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.pagination-wrapper .pagination .page-link:hover {
    color: #495057;
    background-color: #f8f9fc;
    border-color: #d1d3e2;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.pagination-wrapper .pagination .page-item.active .page-link {
    color: #fff;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    transform: translateY(-1px);
}

.pagination-wrapper .pagination .page-item.disabled .page-link {
    color: #adb5bd;
    background-color: #f8f9fa;
    border-color: #e9ecef;
    opacity: 0.6;
    cursor: not-allowed;
    box-shadow: none;
}

.pagination-wrapper .pagination .page-item.disabled .page-link:hover {
    transform: none;
    box-shadow: none;
    background-color: #f8f9fa;
    border-color: #e9ecef;
}

.pagination-wrapper .pagination .page-link i {
    font-size: 0.75rem;
}

/* Results counter styling */
.d-flex .text-muted {
    font-size: 0.875rem;
    font-weight: 500;
    color: #6c757d !important;
    background: #f8f9fc;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    border: 1px solid #e3e6f0;
}

/* Responsive pagination */
@media (max-width: 768px) {
    .pagination-wrapper .pagination .page-link {
        padding: 0.375rem 0.5rem;
        font-size: 0.875rem;
        min-width: 35px;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
    }
    
    .text-muted {
        text-align: center;
    }
}
</style>

<script>
document.getElementById('importForm').addEventListener('submit', function(e) {
    const btn = document.getElementById('importBtn');
    const originalText = btn.innerHTML;
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Importing...';
    btn.disabled = true;
});

function viewReview(reviewId) {
    // Fetch review details via AJAX
    fetch(`/admin/google-reviews/${reviewId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const review = data.review;
                const modalBody = document.getElementById('reviewModalBody');
                modalBody.innerHTML = `
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Reviewer Name:</strong>
                        </div>
                        <div class="col-md-8">${review.reviewer_name}</div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-4">
                            <strong>Rating:</strong>
                        </div>
                        <div class="col-md-8">
                            <div class="text-warning">
                                ${'★'.repeat(review.rating)}${'☆'.repeat(5-review.rating)} (${review.rating}/5)
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-4">
                            <strong>Review Date:</strong>
                        </div>
                        <div class="col-md-8">${new Date(review.review_date).toLocaleDateString()}</div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-4">
                            <strong>Reviews Count:</strong>
                        </div>
                        <div class="col-md-8">${review.reviewer_reviews_count || 'N/A'}</div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-4">
                            <strong>Review Text:</strong>
                        </div>
                        <div class="col-md-8">${review.review_text}</div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-4">
                            <strong>Google Review ID:</strong>
                        </div>
                        <div class="col-md-8">${review.google_review_id}</div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-4">
                            <strong>Status:</strong>
                        </div>
                        <div class="col-md-8">
                            <span class="badge ${review.is_active ? 'bg-success' : 'bg-danger'}">
                                ${review.is_active ? 'Active' : 'Inactive'}
                            </span>
                        </div>
                    </div>
                `;
                $('#reviewModal').modal('show');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading review details');
        });
}

function deleteReview(reviewId) {
    if (confirm('Are you sure you want to delete this review?')) {
        fetch(`/admin/google-reviews/${reviewId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Review deleted successfully');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting review');
        });
    }
}
</script>
@endsection
