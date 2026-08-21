@push('styles')
<style>
.google-reviews-section {
    width: 100%;
    padding: 20px 0;
    margin: 20px 0;
}
.google-reviews-widget {
    background: #f5f5f5;
    border-radius: 12px;
    padding: 20px;
}
.google-reviews-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}
.google-logo-text {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 10px;
}
.google-logo-text img {
    width: 60px;
    height: auto;
}
.google-logo-text h3 {
    font-size: 18px;
    font-weight: 600;
    margin: 0;
    color: #333;
}
.google-left-section {
    display: flex;
    flex-direction: column;
}
.rating-info {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 10px;
}
.rating-number {
    font-size: 24px;
    font-weight: bold;
    color: #333;
}
.stars-container {
    display: flex;
    gap: 1px;
}
.star {
    color: #ffc107;
    font-size: 16px;
}
.star.half {
    background: linear-gradient(90deg, #ffc107 50%, #ddd 50%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.star.empty {
    color: #ddd;
}
.review-count {
    color: #666666;
    font-size: 14px;
    margin-left: 5px;
}
.review-button {
    background: #1557BF;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    font-weight: 500;
    text-decoration: none;
    font-size: 14px;
    transition: background 0.3s;
}
.review-button:hover {
    background: #0D3F8F;
    color: white;
}
.reviews-carousel-section {
    width: 100%;
    padding: 20px 0;
    margin-top: -42px;
}
.reviews-carousel-wrapper {
    position: relative;
    width: 100%;
    margin: 0 -15px;
}
.reviews-carousel {
    display: flex;
    gap: 15px;
    overflow-x: hidden;
    padding: 10px 15px;
    scroll-behavior: smooth;
    width: 100%;
    min-height: 280px; /* Ensure consistent height */
}
.review-card {
    width: 247px;
    min-width: 247px;
    height: 255px;
    background: #F5F5F5;
    border-radius: 12px;
    padding: 16px;
    /* box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border: 1px solid #e0e0e0; */
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.reviewer-info {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 15px;
}
.reviewer-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: #333;
    font-size: 12px;
}
.reviewer-avatar img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}
.reviewer-details h4 {
    margin: 0;
    font-size: 15px;
    font-weight: 600;
    color: #333;
}
.review-stars {
    display: flex;
    gap: 1px;
    margin: 4px 0;
}
.review-stars .star {
    font-size: 20px;
    color: #ffc107;
}
.review-text {
    color: #555;
    line-height: 1.4;
    margin-bottom: 12px;
    font-size: 14px;
}
.read-more-link {
    color: #666;
    text-decoration: none;
    font-size: 13px;
}
.read-more-link:hover {
    color: #333;
}
.review-source {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #666;
    font-size: 12px;
}
.google-icon {
    width: 16px;
    height: 16px;
}

/* New Layout Styles */
.card-top {
    margin-bottom: 8px;
}
.reviewer-info {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 4px;
}
.reviewer-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: #333;
    font-size: 10px;
}
.reviewer-info h4 {
    margin: 0;
    font-size: 13px;
    font-weight: 900;
    color: #333;
}
.review-stars {
    display: flex;
    gap: 1px;
    margin: 0;
}
.review-stars .star {
    font-size: 20px;
    color: #ffc107;
}
.card-middle {
    flex: 1;
    margin-bottom: 8px;
}
.review-text {
    color: #292929;
    line-height: 1.3;
    margin: 0;
    font-size: 17px;
    font-weight: 600;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}
.read-more-link {
    color: #666;
    text-decoration: none;
    font-size: 19px;
    display: block;
    margin-top: 5px;
}
.card-bottom {
    margin-top: auto;
}
.review-source {
    display: flex;
    align-items: flex-start;
    gap: 6px;
    color: #666;
    font-size: 10px;
}
.google-icon {
    width: 32px;
    height: 32px;
    margin-top: 1px;
}
.review-source-text {
    display: flex;
    flex-direction: column;
    line-height: 1.2;
}
.review-source-text .posted-on {
    color: #666;
    font-size: 14px;
}
.review-source-text .google-text {
    color: #1557BF;
    font-size: 15px;
    font-weight: 500;
}

/* Carousel Navigation Buttons */
.carousel-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 35px;
    height: 35px;
    border-radius: 50%;
    border: 2px solid #ddd;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    z-index: 10;
}

.carousel-btn:hover {
    border-color: #4285f4;
    background: #4285f4;
    color: white;
    transform: translateY(-50%) scale(1.1);
}

.carousel-btn i {
    font-size: 14px;
    color: #666;
}

.carousel-btn:hover i {
    color: white;
}

.prev-btn {
    left: 10px;
}

.next-btn {
    right: 10px;
}

/* Hide scrollbar */
.reviews-carousel::-webkit-scrollbar {
    display: none;
}

.reviews-carousel {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

/* Mobile Responsive Styles */
@media (max-width: 768px) {
    .google-reviews-content {
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 20px;
    }

    .google-left-section {
        order: 1;
    }

    .review-button {
        order: 2;
        margin-top: 10px;
    }
}
</style>

<script>
function scrollReviews(direction) {
    const carousel = document.getElementById('reviewsCarousel');
    const cardWidth = 247 + 15; // card width + gap
    const scrollAmount = cardWidth ; // Move exactly two complete cards for better UX

    if (direction === 'left') {
        carousel.scrollLeft -= scrollAmount;
    } else {
        carousel.scrollLeft += scrollAmount;
    }
}

// Auto-hide buttons when at start/end
document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.getElementById('reviewsCarousel');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');

    function updateButtons() {
        if (carousel.scrollLeft <= 0) {
            prevBtn.style.opacity = '0.5';
            prevBtn.style.pointerEvents = 'none';
        } else {
            prevBtn.style.opacity = '1';
            prevBtn.style.pointerEvents = 'auto';
        }

        if (carousel.scrollLeft >= carousel.scrollWidth - carousel.clientWidth) {
            nextBtn.style.opacity = '0.5';
            nextBtn.style.pointerEvents = 'none';
        } else {
            nextBtn.style.opacity = '1';
            nextBtn.style.pointerEvents = 'auto';
        }
    }

    carousel.addEventListener('scroll', updateButtons);
    updateButtons(); // Initial check
});
</script>
@endpush

<section class="google-reviews-section">
    <div class="container">
        <div class="google-reviews-widget">
            <div class="google-reviews-content">
                <div class="google-left-section">
                    <div class="google-logo-text">
                        <img src="https://www.google.com/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png" alt="Google" width="60" height="20" loading="lazy">
                        <span style="font-size: 18px;font-weight: 600;margin: 0;color: #333;">Reviews</span>
                    </div>
                    <div class="rating-info">
                        <div class="rating-number">{{ number_format($overallRating ?? 4.3, 1) }}</div>
                        <div class="stars-container">
                            @php
                                $rating = $overallRating ?? 4.3;
                                $fullStars = floor($rating);
                                $hasHalfStar = ($rating - $fullStars) >= 0.5;
                            @endphp
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $fullStars)
                                    <span class="star">★</span>
                                @elseif($i == $fullStars + 1 && $hasHalfStar)
                                    <span class="star half">★</span>
                                @else
                                    <span class="star empty">★</span>
                                @endif
                            @endfor
                        </div>
                        <div class="review-count">(40)</div>
                    </div>
                                                </div>
                <a href="https://www.google.com/maps/place/Custom+Boxes+Zone+-+Custom+Packaging+Wholesale/@40.6317823,-73.968912,17z/data=!3m1!4b1!4m6!3m5!1s0x89c2458f5f0f88df:0xa323ea2a45d756d6!8m2!3d40.6317823!4d-73.9663371!16s%2Fg%2F11h6lrtrvh?entry=ttu&g_ep=EgoyMDI1MTAxNC4wIKXMDSoASAFQAw%3D%3D" target="_blank" class="review-button">
                    Review us on Google
                </a>
                                                    </div>
                                                </div>
                                            </div>
</section>

<!-- Manual Review Cards Section -->
<section class="reviews-carousel-section">
    <div class="container">
        <div class="reviews-carousel-wrapper">
            <button class="carousel-btn prev-btn" onclick="scrollReviews('left')" aria-label="Previous review">
                <i class="fi-rs-angle-left"></i>
            </button>
            <div class="reviews-carousel" id="reviewsCarousel">
            @if(isset($googleReviews) && $googleReviews->count() > 0)
                @foreach($googleReviews as $review)
                    <div class="review-card">
                        <div class="card-top">
                            <div class="reviewer-info">
                                <div class="reviewer-avatar">
                                    @if($review->reviewer_photo)
                                        <img src="{{ $review->reviewer_photo }}" alt="{{ $review->reviewer_name }}" width="36" height="36" loading="lazy">
                                    @else
                                        {{ substr($review->reviewer_name, 0, 2) }}
                                    @endif
                                </div>
                                <span style="margin: 0;font-size: 15px;font-weight: 600;color: #333;">{{ $review->reviewer_name }}</span>
                            </div>
                            <div class="review-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="star {{ $i <= $review->rating ? '' : 'text-muted' }}">★</span>
                                @endfor
                            </div>
                        </div>
                        <div class="card-middle">
                            <div class="review-text">
                                {{ $review->review_text }}
                            </div>
                            <a style="font-size: 16px" href="{{ route('review.detail', $review->id) }}" class="read-more-link">Read full review from {{ $review->reviewer_name }}</a>
                        </div>
                        <div class="card-bottom">
                            <div class="review-source">
                                <img src="{{ asset('google.png') }}" alt="Google" class="google-icon" width="32" height="32" loading="lazy">
                                <div class="review-source-text">
                                    <span class="posted-on">Posted on</span>
                                    <span class="google-text">Google</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- Manual Review Cards -->
                <div class="review-card">
                    <div class="card-top">
                        <div class="reviewer-info">
                            <div class="reviewer-avatar">IH</div>
                            <h4>Ivan Hryha</h4>
                        </div>
                        <div class="review-stars">
                            <span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span>
                        </div>
                    </div>
                    <div class="card-middle">
                        <div class="review-text">
                            Quality is great, I was completely satisfied with the final product, excellent customer service and fast delivery...
                        </div>
                        <a href="https://www.google.com/maps/place/Custom+Boxes+Zone+-+Custom+Packaging+Wholesale/@40.6317823,-73.968912,17z/data=!3m1!4b1!4m6!3m5!1s0x89c2458f5f0f88df:0xa323ea2a45d756d6!8m2!3d40.6317823!4d-73.9663371!16s%2Fg%2F11h6lrtrvh?entry=ttu&g_ep=EgoyMDI1MTAxNC4wIKXMDSoASAFQAw%3D%3D" target="_blank" class="read-more-link">Read Ivan Hryha's full review on Google</a>
                    </div>
                    <div class="card-bottom">
                        <div class="review-source">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/c1/Google_%22G%22_logo.svg/480px-Google_%22G%22_logo.svg.png" alt="Google" class="google-icon" width="32" height="32" loading="lazy">
                            <div class="review-source-text">
                                <span class="posted-on">Posted on</span>
                                <span class="google-text">Google</span>
                                                    </div>
                                                </div>
                                            </div>
                                            </div>

                <div class="review-card">
                    <div class="card-top">
                        <div class="reviewer-info">
                            <div class="reviewer-avatar">TB</div>
                            <h4>Tori Balsamo</h4>
                        </div>
                        <div class="review-stars">
                            <span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span>
                        </div>
                    </div>
                    <div class="card-middle">
                        <div class="review-text">
                            I don't have enough amazing things to say about iCustom Boxes. The quality is outstanding...
                        </div>
                        <a href="https://www.google.com/maps/place/Custom+Boxes+Zone+-+Custom+Packaging+Wholesale/@40.6317823,-73.968912,17z/data=!3m1!4b1!4m6!3m5!1s0x89c2458f5f0f88df:0xa323ea2a45d756d6!8m2!3d40.6317823!4d-73.9663371!16s%2Fg%2F11h6lrtrvh?entry=ttu&g_ep=EgoyMDI1MTAxNC4wIKXMDSoASAFQAw%3D%3D" target="_blank" class="read-more-link">Read Tori Balsamo's full review on Google</a>
                    </div>
                    <div class="card-bottom">
                        <div class="review-source">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/c1/Google_%22G%22_logo.svg/480px-Google_%22G%22_logo.svg.png" alt="Google" class="google-icon" width="32" height="32" loading="lazy">
                            <div class="review-source-text">
                                <span class="posted-on">Posted on</span>
                                <span class="google-text">Google</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                <div class="review-card">
                    <div class="card-top">
                        <div class="reviewer-info">
                            <div class="reviewer-avatar">PK</div>
                            <h4>Pria Kaushal</h4>
                        </div>
                        <div class="review-stars">
                            <span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span>
                        </div>
                    </div>
                    <div class="card-middle">
                        <div class="review-text">
                            Wonderful, prompt, professional service. Ronan was such a great help throughout the process...
                        </div>
                        <a href="https://www.google.com/maps/place/Custom+Boxes+Zone+-+Custom+Packaging+Wholesale/@40.6317823,-73.968912,17z/data=!3m1!4b1!4m6!3m5!1s0x89c2458f5f0f88df:0xa323ea2a45d756d6!8m2!3d40.6317823!4d-73.9663371!16s%2Fg%2F11h6lrtrvh?entry=ttu&g_ep=EgoyMDI1MTAxNC4wIKXMDSoASAFQAw%3D%3D" target="_blank" class="read-more-link">Read Pria Kaushal's full review on Google</a>
                    </div>
                    <div class="card-bottom">
                        <div class="review-source">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/c1/Google_%22G%22_logo.svg/480px-Google_%22G%22_logo.svg.png" alt="Google" class="google-icon" width="32" height="32" loading="lazy">
                            <div class="review-source-text">
                                <span class="posted-on">Posted on</span>
                                <span class="google-text">Google</span>
                                                    </div>
                                                </div>
                                            </div>
                                            </div>

                <div class="review-card">
                    <div class="card-top">
                        <div class="reviewer-info">
                            <div class="reviewer-avatar">BJ</div>
                            <h4>Brian Johns</h4>
                        </div>
                        <div class="review-stars">
                            <span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span>
                                        </div>
                    </div>
                    <div class="card-middle">
                        <div class="review-text">
                            It's been a pleasure working with iCustomBoxes over the years. Their attention to detail is remarkable...
                        </div>
                        <a href="https://www.google.com/maps/place/Custom+Boxes+Zone+-+Custom+Packaging+Wholesale/@40.6317823,-73.968912,17z/data=!3m1!4b1!4m6!3m5!1s0x89c2458f5f0f88df:0xa323ea2a45d756d6!8m2!3d40.6317823!4d-73.9663371!16s%2Fg%2F11h6lrtrvh?entry=ttu&g_ep=EgoyMDI1MTAxNC4wIKXMDSoASAFQAw%3D%3D" target="_blank" class="read-more-link">Read Brian Johns' full review on Google</a>
                    </div>
                    <div class="card-bottom">
                        <div class="review-source">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/c1/Google_%22G%22_logo.svg/480px-Google_%22G%22_logo.svg.png" alt="Google" class="google-icon" width="32" height="32" loading="lazy">
                            <div class="review-source-text">
                                <span class="posted-on">Posted on</span>
                                <span class="google-text">Google</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="review-card">
                    <div class="card-top">
                        <div class="reviewer-info">
                            <div class="reviewer-avatar">FS</div>
                            <h4>Frances Shine</h4>
                        </div>
                        <div class="review-stars">
                            <span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span>
                        </div>
                    </div>
                    <div class="card-middle">
                        <div class="review-text">
                            Jeff was super helpful and able to get me what I needed in a short time frame. Highly recommend...
                        </div>
                        <a href="https://www.google.com/maps/place/Custom+Boxes+Zone+-+Custom+Packaging+Wholesale/@40.6317823,-73.968912,17z/data=!3m1!4b1!4m6!3m5!1s0x89c2458f5f0f88df:0xa323ea2a45d756d6!8m2!3d40.6317823!4d-73.9663371!16s%2Fg%2F11h6lrtrvh?entry=ttu&g_ep=EgoyMDI1MTAxNC4wIKXMDSoASAFQAw%3D%3D" target="_blank" class="read-more-link">Read Frances Shine's full review on Google</a>
                    </div>
                    <div class="card-bottom">
                        <div class="review-source">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/c1/Google_%22G%22_logo.svg/480px-Google_%22G%22_logo.svg.png" alt="Google" class="google-icon" width="32" height="32" loading="lazy">
                            <div class="review-source-text">
                                <span class="posted-on">Posted on</span>
                                <span class="google-text">Google</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            </div>
            <button class="carousel-btn next-btn" onclick="scrollReviews('right')" aria-label="Next review">
                <i class="fi-rs-angle-right"></i>
            </button>
        </div>
    </div>
</section>
