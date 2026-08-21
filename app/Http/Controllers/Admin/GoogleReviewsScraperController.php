<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GoogleReviewsScraperService;
use Illuminate\Http\Request;

class GoogleReviewsScraperController extends Controller
{
    protected $scraperService;

    public function __construct(GoogleReviewsScraperService $scraperService)
    {
        $this->scraperService = $scraperService;
    }

    public function index()
    {
        return view('admin.google-reviews-scraper.index');
    }

    public function fetchReviews(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'business_location' => 'nullable|string|max:255',
            'place_id' => 'nullable|string|max:255',
            'cid' => 'nullable|string|max:255',
        ]);

        try {
            $result = $this->scraperService->fetchReviewsByBusinessName(
                $request->business_name,
                $request->business_location ?? '',
                $request->place_id ?? null,
                $request->cid ?? null
            );

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'reviews_count' => $result['reviews_count']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    public function getReviews()
    {
        try {
            $reviews = $this->scraperService->getReviews(10);
            $overallRating = $this->scraperService->getOverallRating();
            $totalReviews = $this->scraperService->getTotalReviews();

            return response()->json([
                'success' => true,
                'reviews' => $reviews,
                'overall_rating' => round($overallRating, 1),
                'total_reviews' => $totalReviews
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching reviews: ' . $e->getMessage()
            ]);
        }
    }
}
