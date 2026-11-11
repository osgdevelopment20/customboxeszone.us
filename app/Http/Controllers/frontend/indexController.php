<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\TwitterCard;
use Illuminate\Http\Request;
use App\Models\BookCall;


class indexController extends Controller
{
    public function index()
    {
        $this->seoMetaData(
            sys_settings('site_meta_title'),
            sys_settings('site_meta_description'),
            sys_settings('meta_site_keywords'),
            config('app.url')
        );

        return view('frontend.index');
    }

    public function Search(Request $request)
    {
        $this->seoMetaData(
            sys_settings('site_meta_title'),
            sys_settings('site_meta_description'),
            sys_settings('meta_site_keywords'),
            config('app.url')
        );

        if ($request->input('query')) {
            $search = $request->input('query');

            $results = Product::query()
                ->where("product_name", "LIKE", "%{$search}%")->limit(20)->get();

            $output = '<ul class="dropdown-menu " style="display: block;position: absolute;left: 0; max-height: 280px;overflow-x: auto;">';
            foreach ($results as $product) {

                $output .= '<li><a href="' . route('page', $product->slug) . '" class="dropdown-item"  style="text-decoration: none; color:#088178;">' . e($product->product_name) . '</a></li><br>';
            }
            $output .= '</ul>';
            echo $output;

        }
    }

    public function SearchPage(Request $request)
    {
        $search = $request->input('search');
        
        $products = Product::query()
            ->where("product_name", "LIKE", "%{$search}%")->get();
        return view('frontend.pages.search', compact('products'));
    }

    

    private function seoMetaData($title,$description,$keywords,$url,$image=null)
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
        TwitterCard::setSite('@customboxeszone'); // Add your Twitter handle

        // Additional Meta Tags
        SEOTools::metatags()->addMeta('author', config('app.name'));
        SEOTools::metatags()->addMeta('robots', 'index, follow');
        SEOTools::metatags()->setKeywords([
            $keywords
        ]);
    }

}
