<?php

namespace App\Http\Livewire;

use App\Models\Blog;
use App\Models\Category;
use App\Services\GoogleReviewsService;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\TwitterCard;
use Livewire\Component;

class HomePage extends Component
{
    public $blogs,$homepageSubcategories,$productWithCategory,$googleReviews,$overallRating,$totalReviews;
    public function render()
    {

        $this->seoMetaData(
            sys_settings('site_meta_title'),
            sys_settings('site_meta_description'),
            sys_settings('meta_site_keywords'),
            config('app.url')
        );

        // Cache blogs for 1 hour to reduce DB queries
        $this->blogs = \Cache::remember('homepage_blogs', 3600, function () {
            return Blog::latest()
                ->select(['id', 'title', 'slug','blog_image', 'posted_date'])
                ->limit(2)
                ->get();
        });

        // Cache homepage subcategories for 1 hour
        $this->homepageSubcategories = \Cache::remember('homepage_subcategories', 3600, function () {
            return Category::active()
                    ->where('homepage_category_banner', 1)
                    ->with('media')
                    ->select(['id', 'name', 'slug','homepage_category_banner'])
                    ->orderBy('name')
                    ->get();
        });

        // Cache products with category for 1 hour
        $this->productWithCategory = \Cache::remember('homepage_products_with_category', 3600, function () {
            return Category::active()
                ->where('display_homepage', 1)
                ->select(['categories.id', 'categories.name', 'categories.slug', 'categories.display_homepage'])
                ->with(['products' => function ($query) {
                    $query->where('products.display_homepage', 1)
                        ->select([
                            'products.id',
                            'products.product_name',
                            'products.slug',
                            'products.display_homepage',
                        ])
                        ->with('media')
                        ->orderBy('products.created_at', 'desc');
                }, 'media'])
                ->limit(3)
                ->get();
        });

        // Cache Google Reviews for 6 hours (they don't change frequently)
        $reviewsData = \Cache::remember('homepage_google_reviews', 21600, function () {
            $googleReviewsService = new GoogleReviewsService();
            return [
                'reviews' => $googleReviewsService->getReviews(30),
                'overallRating' => $googleReviewsService->getOverallRating(),
                'totalReviews' => $googleReviewsService->getTotalReviews()
            ];
        });
        
        $this->googleReviews = $reviewsData['reviews'];
        $this->overallRating = $reviewsData['overallRating'];
        $this->totalReviews = $reviewsData['totalReviews'];
        
        return view('livewire.home-page');

    }


    private function seoMetaData($title,$description,$keywords,$url,$image=null): void

    {
        // Basic SEO
        SEOTools::setTitle($title);
        SEOTools::setDescription($description);
        SEOTools::setCanonical($url);

        // OpenGraph
        SEOTools::opengraph()->setTitle($title);
        SEOTools::opengraph()->setDescription($description);
        SEOTools::opengraph()->setSiteName(config('app.name'));
        SEOTools::opengraph()->addProperty('type', 'website');
        SEOTools::opengraph()->addProperty('locale', 'en_US');
        SEOTools::opengraph()->addProperty('image:alt', config('app.name'));
        SEOTools::opengraph()->addImage($image ?? asset('og_image.jpeg'));

        // Twitter Card
        TwitterCard::setType('summary_large_image');
        TwitterCard::setTitle($title);
        TwitterCard::setDescription($description);
        TwitterCard::setUrl($url);
        TwitterCard::setImage($image ?? asset('og_image.jpeg'));
        TwitterCard::setSite('@yourtwitterhandle'); // Add your Twitter handle

        // Additional Meta Tags
        SEOTools::metatags()->addMeta('author', config('app.name'));
        SEOTools::metatags()->addMeta('robots', 'index, follow');
        SEOTools::metatags()->setKeywords([
            $keywords
        ]);
    }

}
