<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GoogleReview;
use App\Models\GoogleReviewSetting;
use App\Services\GoogleReviewsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;

class GoogleReviewController extends Controller
{
    public function index()
    {
        $settings = GoogleReviewSetting::getSettings();
        return view('admin.google-reviews.index', compact('settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'api_key' => 'required|string',
            'place_id' => 'required|string',
        ]);

        $settings = GoogleReviewSetting::first();

        if ($settings) {
            $settings->update($request->only(['api_key', 'place_id', 'is_active']));
        } else {
            GoogleReviewSetting::create($request->only(['api_key', 'place_id', 'is_active']));
        }

        return redirect()->back()->with('success', 'Google Reviews settings saved successfully!');
    }

    public function fetchReviews()
    {
        try {
            Artisan::call('google:fetch-reviews');
            $output = Artisan::output();

            // Clear homepage Google reviews cache
            \Cache::forget('homepage_google_reviews');
            clear_response_cache();

            return response()->json([
                'success' => true,
                'message' => 'Reviews fetched successfully!',
                'output' => $output
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching reviews: ' . $e->getMessage()
            ]);
        }
    }

    public function cronFetch()
    {
        try {
            Artisan::call('google:fetch-reviews');
            return response()->json(['status' => 'success', 'message' => 'Reviews fetched via cron']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function manualImport()
    {
        $reviews = GoogleReview::latest()->paginate(10);
        return view('admin.google-reviews.manual-import', compact('reviews'));
    }

    public function processManualImport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'json_file' => 'required|file|mimes:json|max:10240', // 10MB max
            'overwrite_existing' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $file = $request->file('json_file');
            $jsonContent = file_get_contents($file->getPathname());
            $data = json_decode($jsonContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return redirect()->back()
                    ->with('error', 'Invalid JSON file format: ' . json_last_error_msg());
            }

            // Handle both single object and array of objects structure
            $reviews = [];
            if (isset($data['OrganizationScrapedReviews']) && is_array($data['OrganizationScrapedReviews'])) {
                // Single object structure
                $reviews = $data['OrganizationScrapedReviews'];
            } elseif (is_array($data) && isset($data[0]['OrganizationScrapedReviews'])) {
                // Array of objects structure (like organizations.json)
                $reviews = $data[0]['OrganizationScrapedReviews'];
            } else {
                return redirect()->back()
                    ->with('error', 'JSON file must contain "OrganizationScrapedReviews" array');
            }
            $importedCount = 0;
            $skippedCount = 0;
            $errors = [];

            foreach ($reviews as $index => $review) {
                try {
                    // Validate required fields
                    $requiredFields = ['ReviewId', 'ReviewerName', 'ReviewDate', 'ReviewRateStars', 'ReviewText'];
                    foreach ($requiredFields as $field) {
                        if (!isset($review[$field])) {
                            $errors[] = "Row " . ($index + 1) . ": Missing required field '{$field}'";
                            continue 2;
                        }
                    }

                    // Parse date - handle both ISO format and relative dates
                    $reviewDate = $this->parseReviewDate($review['ReviewDate']);

                    // Prepare data for database
                    $reviewData = [
                        'google_review_id' => $review['ReviewId'],
                        'reviewer_name' => $review['ReviewerName'],
                        'reviewer_photo' => $review['ReviewerAvatarURL'] ?? null,
                        'review_text' => $review['ReviewText'],
                        'rating' => (int) $review['ReviewRateStars'],
                        'review_date' => $reviewDate,
                        'reviewer_reviews_count' => isset($review['ReviewerReviewsCount']) ? (int) $review['ReviewerReviewsCount'] : null,
                        'is_active' => true
                    ];

                    // Check if review already exists
                    $existingReview = GoogleReview::where('google_review_id', $review['ReviewId'])->first();

                    if ($existingReview && !$request->has('overwrite_existing')) {
                        $skippedCount++;
                        continue;
                    }

                    if ($existingReview && $request->has('overwrite_existing')) {
                        $existingReview->update($reviewData);
                        $importedCount++;
                    } else {
                        GoogleReview::create($reviewData);
                        $importedCount++;
                    }

                } catch (\Exception $e) {
                    $errors[] = "Row " . ($index + 1) . ": " . $e->getMessage();
                }
            }

            $message = "Import completed! Imported: {$importedCount}, Skipped: {$skippedCount}";
            if (!empty($errors)) {
                $message .= ", Errors: " . count($errors);
                $message .= "<br><small>Errors: " . implode(', ', array_slice($errors, 0, 5)) . "</small>";
            }

            // Clear homepage Google reviews cache after import
            \Cache::forget('homepage_google_reviews');
            clear_response_cache();

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error processing file: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $review = GoogleReview::findOrFail($id);
            return response()->json([
                'success' => true,
                'review' => $review
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Review not found'
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $review = GoogleReview::findOrFail($id);
            $review->delete();

            // Clear homepage Google reviews cache
            \Cache::forget('homepage_google_reviews');
            clear_response_cache();

            return response()->json([
                'success' => true,
                'message' => 'Review deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting review: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Parse review date from various formats
     */
    private function parseReviewDate($dateString)
    {
        try {
            // Try to parse as ISO date first
            if (preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/', $dateString)) {
                return \Carbon\Carbon::parse($dateString);
            }

            // Handle relative dates like "a year ago", "2 years ago", etc.
            $now = \Carbon\Carbon::now();

            if (strpos($dateString, 'a year ago') !== false || strpos($dateString, '1 year ago') !== false) {
                return $now->subYear();
            } elseif (preg_match('/(\d+) years? ago/', $dateString, $matches)) {
                return $now->subYears((int)$matches[1]);
            } elseif (strpos($dateString, 'a month ago') !== false || strpos($dateString, '1 month ago') !== false) {
                return $now->subMonth();
            } elseif (preg_match('/(\d+) months? ago/', $dateString, $matches)) {
                return $now->subMonths((int)$matches[1]);
            } elseif (strpos($dateString, 'a week ago') !== false || strpos($dateString, '1 week ago') !== false) {
                return $now->subWeek();
            } elseif (preg_match('/(\d+) weeks? ago/', $dateString, $matches)) {
                return $now->subWeeks((int)$matches[1]);
            } elseif (strpos($dateString, 'a day ago') !== false || strpos($dateString, '1 day ago') !== false) {
                return $now->subDay();
            } elseif (preg_match('/(\d+) days? ago/', $dateString, $matches)) {
                return $now->subDays((int)$matches[1]);
            } elseif (strpos($dateString, 'yesterday') !== false) {
                return $now->subDay();
            } elseif (strpos($dateString, 'today') !== false) {
                return $now;
            } else {
                // Try to parse as regular date
                return \Carbon\Carbon::parse($dateString);
            }
        } catch (\Exception $e) {
            // If all else fails, return current date
            return \Carbon\Carbon::now();
        }
    }
}
