@extends('frontend.layouts.app')

@push('head')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Review",
  "itemReviewed": {
    "@type": "LocalBusiness",
    "name": "Custom Boxes Zone",
    "image": "{{ asset('og_image.jpeg') }}",
    "address": {
      "@type": "PostalAddress",
      "addressLocality": "Brooklyn",
      "addressRegion": "NY",
      "addressCountry": "US"
    }
  },
  "reviewRating": {
    "@type": "Rating",
    "ratingValue": "{{ $review->rating }}",
    "bestRating": "5",
    "worstRating": "1"
  },
  "author": {
    "@type": "Person",
    "name": "{{ $review->reviewer_name }}"@if($review->reviewer_photo),
    "image": "{{ $review->reviewer_photo }}"@endif
  },
  "datePublished": "{{ \Carbon\Carbon::parse($review->review_date)->toIso8601String() }}",
  "reviewBody": {{ json_encode($review->review_text) }},
  "publisher": {
    "@type": "Organization",
    "name": "Custom Boxes Zone"
  }
}
</script>
@endpush

@push('styles')
<style>
.review-detail-page {
    padding: 40px 0;
    background: #f8f9fa;
}

.review-detail-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
}

.review-detail-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 40px;
    margin-top: 30px;
}

.review-main-content {
    background: white;
    border-radius: 12px;
    padding: 40px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.review-sidebar {
    display: flex;
    flex-direction: column;
    gap: 30px;
}

.sidebar-widget {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.review-header {
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 25px;
    margin-bottom: 30px;
}

.reviewer-info {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 20px;
}

.reviewer-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: white;
    font-size: 24px;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.reviewer-avatar img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}

.reviewer-details h1 {
    font-size: 28px;
    font-weight: 700;
    color: #333;
    margin: 0 0 10px 0;
}

.review-meta {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 15px;
}

.review-stars {
    display: flex;
    gap: 3px;
}

.review-stars .star {
    font-size: 24px;
    color: #ffc107;
}

.review-stars .star.empty {
    color: #ddd;
}

.review-date {
    color: #666;
    font-size: 16px;
}

.review-rating-badge {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 14px;
}

.review-content {
    font-size: 18px;
    line-height: 1.8;
    color: #444;
    margin-bottom: 30px;
}

.review-source-info {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #4285f4;
}

.google-icon {
    width: 40px;
    height: 40px;
}

.source-text {
    color: #666;
    font-size: 16px;
}

.source-text .google-text {
    color: #4285f4;
    font-weight: 600;
}

.sidebar-title {
    font-size: 20px;
    font-weight: 700;
    color: #333;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #f0f0f0;
}

.other-review-item {
    display: flex;
    gap: 15px;
    padding: 15px 0;
    border-bottom: 1px solid #f0f0f0;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
}

.other-review-item:hover {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    margin: 0 -15px;
    color: inherit;
}

.other-review-item:last-child {
    border-bottom: none;
}

.other-reviewer-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: #333;
    font-size: 14px;
    flex-shrink: 0;
}

.other-reviewer-avatar img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}

.other-review-content h4 {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin: 0 0 5px 0;
}

.other-review-text {
    font-size: 14px;
    color: #666;
    line-height: 1.4;
    margin-bottom: 8px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.other-review-stars {
    display: flex;
    gap: 2px;
}

.other-review-stars .star {
    font-size: 14px;
    color: #ffc107;
}

.other-review-stars .star.empty {
    color: #ddd;
}

.overall-rating-widget {
    text-align: center;
    padding: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px;
}

.overall-rating-number {
    font-size: 48px;
    font-weight: 700;
    margin-bottom: 10px;
}

.overall-stars {
    display: flex;
    justify-content: center;
    gap: 3px;
    margin-bottom: 15px;
}

.overall-stars .star {
    font-size: 20px;
    color: #ffc107;
}

.overall-review-count {
    font-size: 16px;
    opacity: 0.9;
}

.back-to-reviews {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #4285f4;
    text-decoration: none;
    font-weight: 500;
    margin-bottom: 20px;
    transition: color 0.3s ease;
}

.back-to-reviews:hover {
    color: #3367d6;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .review-detail-content {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .review-main-content {
        padding: 25px;
    }

    .reviewer-info {
        flex-direction: column;
        text-align: center;
        gap: 15px;
    }

    .reviewer-details h1 {
        font-size: 24px;
    }

    .review-content {
        font-size: 16px;
    }

    .review-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
}
</style>
@endpush

@section('content')
<div class="review-detail-page">
    <div class="review-detail-container">


        <div class="review-detail-content">
            <!-- Main Review Content -->
            <div class="review-main-content">
                <div class="review-header">
                    <div class="reviewer-info">
                        <div class="reviewer-avatar">
                            @if($review->reviewer_photo)
                                <img src="{{ $review->reviewer_photo }}" alt="{{ $review->reviewer_name }}">
                            @else
                                {{ substr($review->reviewer_name, 0, 2) }}
                            @endif
                        </div>
                        <div class="reviewer-details">
                            <h1>{{ $review->reviewer_name }}</h1>
                            <div class="review-meta">
                                <div class="review-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="star {{ $i <= $review->rating ? '' : 'empty' }}">★</span>
                                    @endfor
                                </div>
                                <div class="review-date">
                                    {{ \Carbon\Carbon::parse($review->review_date)->format('F d, Y') }}
                                </div>
                                <div class="review-rating-badge">
                                    {{ $review->rating }}/5 Stars
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="review-content">
                    {{ $review->review_text }}
                </div>

                <div class="review-source-info">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/c1/Google_%22G%22_logo.svg/480px-Google_%22G%22_logo.svg.png" alt="Google" class="google-icon">
                    <div class="source-text">
                        This review was posted on <span class="google-text">Google</span>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="review-sidebar">
                <!-- Overall Rating Widget -->
                <div class="sidebar-widget overall-rating-widget">
                    <div class="overall-rating-number">{{ number_format($overallRating, 1) }}</div>
                    <div class="overall-stars">
                        @php
                            $rating = $overallRating;
                            $fullStars = floor($rating);
                            $hasHalfStar = ($rating - $fullStars) >= 0.5;
                        @endphp
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $fullStars)
                                <span class="star">★</span>
                            @elseif($i == $fullStars + 1 && $hasHalfStar)
                                <span class="star">★</span>
                            @else
                                <span class="star empty">★</span>
                            @endif
                        @endfor
                    </div>
                    <div class="overall-review-count">
                        Based on {{ $totalReviews }} reviews
                    </div>
                </div>

                <!-- Recent Reviews -->
                <div class="sidebar-widget">
                    <h3 class="sidebar-title">Recent Reviews</h3>
                    @foreach($recentReviews as $recentReview)
                        <a href="{{ route('review.detail', $recentReview->id) }}" class="other-review-item">
                            <div class="other-reviewer-avatar">
                                @if($recentReview->reviewer_photo)
                                    <img src="{{ $recentReview->reviewer_photo }}" alt="{{ $recentReview->reviewer_name }}">
                                @else
                                    {{ substr($recentReview->reviewer_name, 0, 2) }}
                                @endif
                            </div>
                            <div class="other-review-content">
                                <h4>{{ $recentReview->reviewer_name }}</h4>
                                <div class="other-review-text">
                                    {{ $recentReview->review_text }}
                                </div>
                                <div class="other-review-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="star {{ $i <= $recentReview->rating ? '' : 'empty' }}">★</span>
                                    @endfor
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <!-- Call to Action -->
                <div class="sidebar-widget">
                    <h3 class="sidebar-title">Share Your Experience</h3>
                    <p style="color: #666; margin-bottom: 20px;">
                        Had a great experience with us? We'd love to hear about it!
                    </p>
                    <a href="https://www.google.com/maps/place/Custom+Boxes+Zone+-+Custom+Packaging+Wholesale/@40.6317823,-73.968912,17z/data=!3m1!4b1!4m6!3m5!1s0x89c2458f5f0f88df:0xa323ea2a45d756d6!8m2!3d40.6317823!4d-73.9663371!16s%2Fg%2F11h6lrtrvh?entry=ttu&g_ep=EgoyMDI1MTAxNC4wIKXMDSoASAFQAw%3D%3D"
                       target="_blank"
                       class="btn btn-primary"
                       style="width: 100%; padding: 12px; background: #4285f4; border: none; border-radius: 8px; color: white; text-decoration: none; font-weight: 600;">
                        Write a Review on Google
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
