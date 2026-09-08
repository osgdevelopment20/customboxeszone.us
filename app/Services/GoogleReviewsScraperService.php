<?php

namespace App\Services;

class GoogleReviewsScraperService
{
    public function fetchReviewsByBusinessName(
        string $businessName,
        string $businessLocation = '',
        ?string $placeId = null,
        ?string $cid = null
    ): array {
        $reviewsService = $this->reviewsService();
        $result = $reviewsService->fetchReviews();

        if ($result === null) {
            return [
                'success' => false,
                'message' => 'Unable to fetch Google reviews. Please confirm the Google Reviews API key and Place ID in settings.',
                'reviews_count' => 0,
            ];
        }

        return [
            'success' => true,
            'message' => 'Google reviews fetched successfully.',
            'reviews_count' => $reviewsService->getTotalReviews(),
        ];
    }

    public function getReviews(int $limit = 10)
    {
        return $this->reviewsService()->getReviews($limit);
    }

    public function getOverallRating()
    {
        return $this->reviewsService()->getOverallRating();
    }

    public function getTotalReviews(): int
    {
        return $this->reviewsService()->getTotalReviews();
    }

    private function reviewsService(): GoogleReviewsService
    {
        return app(GoogleReviewsService::class);
    }
}
