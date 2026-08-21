<?php

namespace App\Services;

use App\Models\GoogleReview;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleReviewsService
{
    protected $apiKey;
    protected $placeId;

    public function __construct()
    {
        $settings = \App\Models\GoogleReviewSetting::getSettings();
        $this->apiKey = $settings->api_key ?? config('services.google.api_key');
        $this->placeId = $settings->place_id ?? config('services.google.place_id');
    }

    public function fetchReviews()
    {
        try {
            $response = Http::get('https://maps.googleapis.com/maps/api/place/details/json', [
                'place_id' => $this->placeId,
                'fields' => 'reviews,rating,user_ratings_total',
                'key' => $this->apiKey
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['result']['reviews'])) {
                    $this->processReviews($data['result']['reviews']);
                    return [
                        'rating' => $data['result']['rating'] ?? 0,
                        'total_reviews' => $data['result']['user_ratings_total'] ?? 0
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::error('Google Reviews API Error: ' . $e->getMessage());
        }

        return null;
    }

    protected function processReviews($reviews)
    {
        foreach ($reviews as $review) {
            GoogleReview::updateOrCreate(
                ['google_review_id' => $review['time']],
                [
                    'reviewer_name' => $review['author_name'],
                    'reviewer_photo' => $review['profile_photo_url'] ?? null,
                    'review_text' => $review['text'],
                    'rating' => $review['rating'],
                    'review_date' => \Carbon\Carbon::createFromTimestamp($review['time']),
                    'is_active' => true
                ]
            );
        }
    }

    public function getReviews($limit = 5)
    {
        return GoogleReview::active()
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getOverallRating()
    {
        return GoogleReview::active()->avg('rating');
    }

    public function getTotalReviews()
    {
        return GoogleReview::active()->count();
    }
}
