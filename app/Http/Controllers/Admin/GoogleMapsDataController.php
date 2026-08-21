<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleMapsDataController extends Controller
{
    /**
     * Get raw Google Maps data for debugging
     */
    public function getRawData(Request $request)
    {
        try {
            $placeId = $request->get('place_id');
            $cid = $request->get('cid');
            $businessName = $request->get('business_name');
            
            if (!$placeId && !$cid && !$businessName) {
                return response()->json([
                    'success' => false,
                    'error' => 'Please provide place_id, cid, or business_name parameter',
                    'example_urls' => [
                        'place_id' => url('/api/google-maps-data/raw-data') . '?place_id=ChlJ34gPX49FwokR1lbXRSrqI6M',
                        'cid' => url('/api/google-maps-data/raw-data') . '?cid=11755496919648392918',
                        'business_name' => url('/api/google-maps-data/raw-data') . '?business_name=Custom Boxes Zone'
                    ]
                ], 400);
            }

            $results = [];
            
            // Try Place ID
            if ($placeId) {
                $results['place_id'] = $this->fetchWithPlaceId($placeId);
            }
            
            // Try CID
            if ($cid) {
                $results['cid'] = $this->fetchWithCid($cid);
            }
            
            // Try Business Name
            if ($businessName) {
                $results['business_name'] = $this->fetchWithBusinessName($businessName);
            }

            return response()->json([
                'success' => true,
                'data' => $results,
                'timestamp' => now()->toISOString(),
                'message' => 'Data fetched successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Server error: ' . $e->getMessage(),
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }

    protected function fetchWithPlaceId($placeId)
    {
        try {
            $url = "https://www.google.com/maps/place/?q=place_id:{$placeId}";
            
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Accept-Language' => 'en-US,en;q=0.5',
                'Accept-Encoding' => 'gzip, deflate',
                'Connection' => 'keep-alive',
                'Upgrade-Insecure-Requests' => '1',
            ])->timeout(30)->get($url);

            return [
                'url' => $url,
                'status' => $response->status(),
                'success' => $response->successful(),
                'html_length' => strlen($response->body()),
                'json_data' => $this->extractJsonData($response->body()),
                'review_patterns' => $this->findReviewPatterns($response->body())
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
                'url' => $url ?? 'N/A'
            ];
        }
    }

    protected function fetchWithCid($cid)
    {
        try {
            $url = "https://www.google.com/maps?cid={$cid}";
            
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Accept-Language' => 'en-US,en;q=0.5',
                'Accept-Encoding' => 'gzip, deflate',
                'Connection' => 'keep-alive',
                'Upgrade-Insecure-Requests' => '1',
            ])->timeout(30)->get($url);

            return [
                'url' => $url,
                'status' => $response->status(),
                'success' => $response->successful(),
                'html_length' => strlen($response->body()),
                'json_data' => $this->extractJsonData($response->body()),
                'review_patterns' => $this->findReviewPatterns($response->body())
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
                'url' => $url ?? 'N/A'
            ];
        }
    }

    protected function fetchWithBusinessName($businessName)
    {
        try {
            $searchQuery = urlencode($businessName);
            $url = "https://www.google.com/maps/search/{$searchQuery}";
            
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Accept-Language' => 'en-US,en;q=0.5',
                'Accept-Encoding' => 'gzip, deflate',
                'Connection' => 'keep-alive',
                'Upgrade-Insecure-Requests' => '1',
            ])->timeout(30)->get($url);

            return [
                'url' => $url,
                'status' => $response->status(),
                'success' => $response->successful(),
                'html_length' => strlen($response->body()),
                'json_data' => $this->extractJsonData($response->body()),
                'review_patterns' => $this->findReviewPatterns($response->body())
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
                'url' => $url ?? 'N/A'
            ];
        }
    }

    protected function extractJsonData($html)
    {
        $jsonData = [];
        
        // Look for various JSON patterns
        $patterns = [
            '/window\.APP_INITIALIZATION_STATE\s*=\s*(\[.*?\]);/',
            '/window\.APP_INITIALIZATION_DATA\s*=\s*(\[.*?\]);/',
            '/window\._INITIAL_DATA_\s*=\s*(\[.*?\]);/',
            '/"reviews":\s*(\[.*?\])/',
            '/"userReviews":\s*(\[.*?\])/',
            '/<script[^>]*type="application\/ld\+json"[^>]*>(.*?)<\/script>/s'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match_all($pattern, $html, $matches)) {
                foreach ($matches[1] as $match) {
                    $decoded = json_decode($match, true);
                    if ($decoded) {
                        $jsonData[] = [
                            'pattern' => $pattern,
                            'data' => $decoded,
                            'size' => strlen($match)
                        ];
                    }
                }
            }
        }
        
        return $jsonData;
    }

    protected function findReviewPatterns($html)
    {
        $patterns = [];
        
        // Look for review-related text patterns
        $reviewKeywords = [
            'review', 'rating', 'star', 'comment', 'feedback',
            'author_name', 'review_text', 'reviewBody', 'reviewRating'
        ];
        
        foreach ($reviewKeywords as $keyword) {
            if (stripos($html, $keyword) !== false) {
                $patterns[] = [
                    'keyword' => $keyword,
                    'found' => true,
                    'context' => $this->getContextAroundKeyword($html, $keyword)
                ];
            }
        }
        
        return $patterns;
    }

    protected function getContextAroundKeyword($html, $keyword, $contextLength = 100)
    {
        $position = stripos($html, $keyword);
        if ($position === false) {
            return null;
        }
        
        $start = max(0, $position - $contextLength);
        $end = min(strlen($html), $position + strlen($keyword) + $contextLength);
        
        return substr($html, $start, $end - $start);
    }
}
