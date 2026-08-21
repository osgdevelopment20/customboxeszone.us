<?php

namespace App\Http\Controllers\frontend;
use App\Http\Controllers\Controller;
use App\Models\GoogleReview;
use App\Services\GoogleReviewsService;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\TwitterCard;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReviewController extends Controller
{
    public function show($id)
    {
        try {
            // Get the specific review
            $review = GoogleReview::where('id', $id)
                ->where('is_active', true)
                ->firstOrFail();

            // Get other reviews for sidebar (excluding current review)
            $otherReviews = GoogleReview::where('is_active', true)
                ->where('id', '!=', $id)
                ->latest()
                ->limit(10)
                ->get();

            // Get overall rating and total reviews count
            $googleReviewsService = new GoogleReviewsService();
            $overallRating = $googleReviewsService->getOverallRating();
            $totalReviews = $googleReviewsService->getTotalReviews();

            // Get recent reviews for sidebar
            $recentReviews = GoogleReview::where('is_active', true)
                ->where('id', '!=', $id)
                ->latest()
                ->limit(5)
                ->get();

            $this->seoMetaData(
                $review->reviewer_name . ' - Customer Review | Custom Boxes Zone',
                'Read ' . $review->reviewer_name . '\'s ' . $review->rating . '-star review about Custom Boxes Zone. ' . Str::limit($review->review_text, 150),
                'customer review, ' . $review->reviewer_name . ', custom boxes, packaging review',
                route('review.detail', $review->id),
                $review->reviewer_photo
            );

            return view('frontend.pages.review-detail', compact(
                'review',
                'otherReviews',
                'overallRating',
                'totalReviews',
                'recentReviews'
            ));

        } catch (\Exception $e) {
            abort(404, 'Review not found');
        }
    }

    private function seoMetaData($title, $description, $keywords, $url, $image = null): void
    {
        // Basic SEO
        SEOTools::setTitle($title);
        SEOTools::setDescription($description);
        SEOTools::setCanonical($url);

        // OpenGraph
        SEOTools::opengraph()->setTitle($title);
        SEOTools::opengraph()->setDescription($description);
        SEOTools::opengraph()->setSiteName(config('app.name'));
        SEOTools::opengraph()->addProperty('type', 'article');
        SEOTools::opengraph()->addProperty('locale', 'en_US');
        SEOTools::opengraph()->addProperty('image:alt', $title);
        SEOTools::opengraph()->setUrl($url);
        SEOTools::opengraph()->addImage($image ?? asset('og_image.jpeg'));

        // Twitter Card
        TwitterCard::setType('summary_large_image');
        TwitterCard::setTitle($title);
        TwitterCard::setDescription($description);
        TwitterCard::setUrl($url);
        TwitterCard::setImage($image ?? asset('og_image.jpeg'));
        TwitterCard::setSite('@customboxeszone');

        // Additional Meta Tags
        SEOTools::metatags()->addMeta('author', config('app.name'));
        SEOTools::metatags()->addMeta('robots', 'index, follow');
        SEOTools::metatags()->setKeywords(explode(', ', $keywords));
    }
}
