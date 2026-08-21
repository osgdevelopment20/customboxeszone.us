<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Product;
use App\Services\GoogleReviewsService;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\TwitterCard;
use Illuminate\Support\Facades\Log;
use Spatie\SchemaOrg\Schema;
use DateTime;

class PageRenderer extends Component
{
    public $slug;
    public $pagePrefix;
    public $pageNumber;
    public $productsPerPage = 12;
    public $productsToLoad = 4;
    public $totalProductsLoaded = 0;

    protected $queryString = ['pagePrefix', 'pageNumber'];

    public function mount($slug, $pagePrefix = null, $pageNumber = null)
    {
        if ($pagePrefix && !$pageNumber) {
            abort(404);
        }

        $this->slug = $slug;
        $this->pagePrefix = $pagePrefix;
        $this->pageNumber = $pageNumber;
        $this->totalProductsLoaded = $this->productsPerPage;
    }

    public function loadMore()
    {
        $this->totalProductsLoaded += $this->productsToLoad;
    }

    public function render()
    {
        try {
            if ($categoryToSubCategory = $this->categoryToSubCategory()) {
                $this->setSeoMetaData(
                    $categoryToSubCategory->meta_title ?? $categoryToSubCategory->title,
                    $categoryToSubCategory->meta_description,
                    $categoryToSubCategory->meta_keywords ?? sys_settings('meta_site_keywords'),
                    route('page', $categoryToSubCategory->slug),
                    $categoryToSubCategory->media->first()?->getUrl()
                );
                return view('livewire.pages.category-to-subcategory', [
                    'categoryToSubCategory' => $categoryToSubCategory,
                    'breadcrumbData' => $this->generateCategoryBreadcrumb($categoryToSubCategory),
                    'schemaData' => $this->generateCategorySchema($categoryToSubCategory)
                ])->extends('frontend.layouts.app')
                    ->section('content');

            }

            if ($subCategoryToProducts = $this->subCategoryToProducts()) {
                $this->setSeoMetaData(
                    $subCategoryToProducts->meta_title ?? $subCategoryToProducts->title,
                    $subCategoryToProducts->meta_description,
                    $subCategoryToProducts->meta_keywords ?? sys_settings('meta_site_keywords'),
                    route('page', $subCategoryToProducts->slug),
                    $subCategoryToProducts->media->first()?->getUrl()
                );

                // Get paginated products
                $allProducts = $subCategoryToProducts->products;
                $displayedProducts = $allProducts->take($this->totalProductsLoaded);
                $hasMoreProducts = $allProducts->count() > $this->totalProductsLoaded;

                return view('livewire.pages.subcategory-to-products', [
                    'subCategoryToProducts' => $subCategoryToProducts,
                    'displayedProducts' => $displayedProducts,
                    'totalProducts' => $allProducts->count(),
                    'hasMoreProducts' => $hasMoreProducts,
                    'breadcrumbData' => $this->generateSubcategoryBreadcrumb($subCategoryToProducts),
                    'schemaData' => $this->generateSubcategorySchema($subCategoryToProducts)
                ])->extends('frontend.layouts.app')
                    ->section('content');
            }

            if ($product = $this->products()) {
                $this->setSeoMetaData(
                    $product->meta_title ?? $product->product_title,
                    $product->meta_description,
                    $product->meta_keywords ?? sys_settings('meta_site_keywords'),
                    route('page', $product->slug),
                    $product->media->first()?->getUrl()
                );

                // Check if product has categories and get main category
                if (empty($product->category) || !isset($product->category[0])) {
                    Log::warning('Product has no category', ['product_id' => $product->id, 'slug' => $product->slug]);
                    abort(404);
                }

                $mainCategory = Category::where('id', $product->category[0]->parent_id)->first();
                if (!$mainCategory) {
                    Log::warning('Main category not found for product', [
                        'product_id' => $product->id,
                        'category_id' => $product->category[0]->id,
                        'parent_id' => $product->category[0]->parent_id
                    ]);
                    abort(404);
                }
                $productFaqs = $this->getFaqsProducts($product);

                // Load Google Reviews data
                $googleReviewsService = new GoogleReviewsService();
                $googleReviews = $googleReviewsService->getReviews(30);
                $overallRating = $googleReviewsService->getOverallRating();
                $totalReviews = $googleReviewsService->getTotalReviews();

                return view('livewire.pages.product-detail', [
                    'product' => $product,
                    'relatedProducts' => $this->getRelatedProducts($product),
                    'clientStories' => $this->getClientStories($product),
                    'productFaqs' => $productFaqs,
                    'variations' => $product->variations()->where('status', 1)->orderBy('sort_order')->get(),
                    'breadcrumbData' => $this->generateProductBreadcrumb($product,$mainCategory),
                    'schemaData' => $this->generateProductSchema($product,$mainCategory,$productFaqs),
                    'faqSchema' => $this->generateProductFaqSchema($product),
                    'googleReviews' => $googleReviews,
                    'overallRating' => $overallRating,
                    'totalReviews' => $totalReviews
                ])->extends('frontend.layouts.app')
                    ->section('content');
            }

            abort(404);
        } catch (\Exception $e) {
            Log::error('Error in PageRenderer', [
                'message' => $e->getMessage(),
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'slug' => $this->slug,
                'pagePrefix' => $this->pagePrefix,
                'pageNumber' => $this->pageNumber,
                'trace' => $e->getTraceAsString()
            ]);
            abort(404);
        }
    }

    private function categoryToSubCategory()
    {
        return Category::with(['media', 'categories'])
            ->where('slug', $this->slug)
            ->where('parent_id', 0)
            ->first();
    }
    private function subCategoryToProducts()
    {
        return Category::with(['media', 'products', 'procategories'])
            ->where('slug', $this->slug)
            ->whereNotNull('parent_id')
            ->first();
    }
    private function products()
    {
        return Product::with(['media', 'category', 'variations.media'])
            ->where('slug', $this->slug)
            ->first();
    }
    private function getRelatedProducts($product)
    {
        $relatedProductIds = json_decode($product->related_products) ?: [];
        return !empty($relatedProductIds)
            ? Product::whereIn('id', $relatedProductIds)->get()
            : collect([]);
    }
    private function getFaqsProducts($product)
    {
        return json_decode($product->faqs, true) ?: [];
    }
    
    private function getClientStories($product)
    {
        return $product->clientStoriesResolved();
    }
    
    private function setSeoMetaData($title, $description, $keywords, $url, $image = null): void
    {
        SEOTools::setTitle($title);
        SEOTools::setDescription($description);
        SEOTools::setCanonical($url);

        SEOTools::opengraph()->setTitle($title);
        SEOTools::opengraph()->setDescription($description);
        SEOTools::opengraph()->setSiteName(config('app.name'));
        SEOTools::opengraph()->setUrl($url);
        SEOTools::opengraph()->addProperty('type', 'website');
        SEOTools::opengraph()->addProperty('locale', 'en_US');
        SEOTools::opengraph()->addProperty('image:alt', config('app.name'));
        SEOTools::opengraph()->addImage($image ?? asset('og_image.jpeg'));

        TwitterCard::setType('summary_large_image');
        TwitterCard::setTitle($title);
        TwitterCard::setDescription($description);
        TwitterCard::setUrl($url);
        TwitterCard::setImage($image ?? asset('og_image.jpeg'));

        SEOTools::metatags()->addMeta('author', config('app.name'));
        SEOTools::metatags()->addMeta('robots', 'index, follow');
        SEOTools::metatags()->setKeywords([$keywords]);
    }

    private function generateCategorySchema($categoryToSubCategory)
    {
        // for list schema
        $itemList = Schema::itemList();
        $itemListElements = [];

        // Add home page as first element
        $itemListElements[] = Schema::listItem()
            ->position(1)
            ->name('Home')
            ->item(route('index'));
        $itemListElements[] = Schema::listItem()
            ->position(2)
            ->name($categoryToSubCategory->name)
            ->item(route('page', $categoryToSubCategory->slug));

        // Add categories with incremented positions
        foreach ($categoryToSubCategory->categories as $index => $category) {
            $itemListElements[] = Schema::listItem()
                ->position($index + 3)  // +3 because home is position 1
                ->name($category->name)
                ->item(route('page', $category->slug));
        }

        // Add the elements to the itemList
        $itemList->itemListElement($itemListElements);

        // Add the list type and name
        $itemList->type('ItemList')
            ->name('Categories');

        // breadcrumb schema

        $breadcrumbSchema = [
            "@context" => "https://schema.org/",
            "@type" => "BreadcrumbList",
            "itemListElement" => [
                [
                    "@type" => "ListItem",
                    "position" => 1,
                    "name" => "Home",
                    "item" => route('index')
                ],
                [
                    "@type" => "ListItem",
                    "position" => 2,
                    "name" => "Box By Industry",
                    "item" => route('allcategories')
                ],
                [
                    "@type" => "ListItem",
                    "position" => 3,
                    "name" => $categoryToSubCategory->name,
                    "item" => route('page',$categoryToSubCategory->slug)
                ],

            ]
        ];

        $breadcrumbScript = '<script type="application/ld+json">' . json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';

        // for webpage schema
        $schemaWebPage = Schema::webPage()
            ->name($categoryToSubCategory->name)
            ->description($categoryToSubCategory->mata_description)
            ->url(route('index'))
            ->identifier(route('index') . "#webpage")
            ->datePublished($categoryToSubCategory->created_at)
            ->dateModified($categoryToSubCategory->updated_at)
            ->inLanguage('en-US');

        return [
            'webpage' =>  $schemaWebPage->toScript(),
            'breadcrumb' => $breadcrumbScript,
            'itemList' => $itemList->toScript(),
        ];
    }

    private function generateSubcategorySchema($subCategoryToProducts)
    {
        // for list schema
        $itemList = Schema::itemList();
        $itemListElements = [];

        // Add home page as first element
        $itemListElements[] = Schema::listItem()
            ->position(1)
            ->name('Home')
            ->item(route('index'));
        $itemListElements[] = Schema::listItem()
            ->position(2)
            ->name($subCategoryToProducts->name)
            ->item(route('page', $subCategoryToProducts->slug));

        // Add categories with incremented positions
        foreach ($subCategoryToProducts->products as $index => $product) {
            $itemListElements[] = Schema::listItem()
                ->position($index + 3)  // +3 because home is position 1
                ->name($product->product_title)
                ->item(route('page', $product->slug));
        }

        // Add the elements to the itemList
        $itemList->itemListElement($itemListElements);

        // Add the list type and name
        $itemList->type('ItemList')
            ->name('Categories');

        // breadcrumb schema

        $breadcrumbSchema = [
            "@context" => "https://schema.org/",
            "@type" => "BreadcrumbList",
            "itemListElement" => [
                [
                    "@type" => "ListItem",
                    "position" => 1,
                    "name" => "Home",
                    "item" => route('index')
                ],
                [
                    "@type" => "ListItem",
                    "position" => 2,
                    "name" => "Box By Industry",
                    "item" => route('allcategories')
                ],
                [
                    "@type" => "ListItem",
                    "position" => 3,
                    "name" => $subCategoryToProducts->procategories->name,
                    "item" => route('page',$subCategoryToProducts->procategories->slug)
                ],
                [
                    "@type" => "ListItem",
                    "position" => 4,
                    "name" => $subCategoryToProducts->name,
                    "item" => route('page',$subCategoryToProducts->slug)
                ],

            ]
        ];

        $breadcrumbScript = '<script type="application/ld+json">' . json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';


        // for webpage schema
        $schemaWebPage = Schema::webPage()
            ->name($subCategoryToProducts->name)
            ->description($subCategoryToProducts->mata_description)
            ->url(route('index'))
            ->identifier(route('index') . "#webpage")
            ->datePublished($subCategoryToProducts->created_at)
            ->dateModified($subCategoryToProducts->updated_at)
            ->inLanguage('en-US');

        return [
            'webpage' =>  $schemaWebPage->toScript(),
            'breadcrumb' => $breadcrumbScript,
            'itemList' => $itemList->toScript(),
        ];
    }

    private function generateProductSchema($product,$mainCategory,$productFaqs)
    {
        // for webpage schema
        $schemaWebPage = Schema::webPage()
            ->name($product->product_title)
            ->description($product->mata_description)
            ->url(route('index'))
            ->identifier(route('index') . "#webpage")
            ->datePublished($product->created_at)
            ->dateModified($product->updated_at)
            ->inLanguage('en-US');

        $breadcrumbSchema = [
            "@context" => "https://schema.org/",
            "@type" => "BreadcrumbList",
            "itemListElement" => [
                [
                    "@type" => "ListItem",
                    "position" => 1,
                    "name" => "Home",
                    "item" => route('index')
                ],
                [
                    "@type" => "ListItem",
                    "position" => 2,
                    "name" => "Box By Industry",
                    "item" => route('allcategories')
                ],
                [
                    "@type" => "ListItem",
                    "position" => 3,
                    "name" => $mainCategory->name,
                    "item" => route('page', $mainCategory->slug)
                ],
                [
                    "@type" => "ListItem",
                    "position" => 4,
                    "name" => $product->category[0]->name,
                    "item" => route('page', $product->category[0]->slug)
                ],
                [
                    "@type" => "ListItem",
                    "position" => 5,
                    "name" => $product->product_name,
                    "item" => route('page', $product->slug)
                ],

            ]
        ];

        $breadcrumbSchema = '<script type="application/ld+json">' . json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';

        // product FAQs schema
        $faqSchema = '';
        if (json_last_error() === JSON_ERROR_NONE && is_array($productFaqs) && count($productFaqs) > 0) {
            $schema = Schema::fAQPage()
                ->identifier(route('faq'))
                ->name('Frequently Asked Questions')
                ->description('Have a question in mind? Navigate through these questions to find answers. However, if you cannot see your question in the list, feel free to drop a call.')
                ->setProperty('@id', request()->url() . '#faq');
            $mainEntity = [];
            foreach ($productFaqs as $faq) {
                if (empty($faq['question']) || empty($faq['answer']) || !is_string($faq['question']) || !is_string($faq['answer'])) {
                    continue;
                }
                $mainEntity[] = Schema::question()
                    ->name(trim($faq['question']))
                    ->acceptedAnswer(
                        Schema::answer()
                            ->text(trim($faq['answer']))
                    );
            }

            // Only generate schema if there are valid FAQs
            if (!empty($mainEntity)) {
                $schema->mainEntity($mainEntity);
                $faqSchema = $schema->toScript();
            }
        }

        return [
            'webpage' =>   $schemaWebPage->toScript(),
            'breadcrumb' => $breadcrumbSchema,
            'faq' => $faqSchema,
        ];
    }

    private function generateProductFaqSchema($product)
    {
        $productFaqs = json_decode($product->faqs, true) ?: [];
        if (empty($productFaqs)) {
            return '';
        }

        $schema = Schema::fAQPage()
            ->identifier(route('faq'))
            ->name('Frequently Asked Questions')
            ->description('Have a question in mind? Navigate through these questions to find answers.')
            ->setProperty('@id', request()->url() . '#faq');

        $mainEntity = [];
        foreach ($productFaqs as $faq) {
            if (empty($faq['question']) || empty($faq['answer'])) {
                continue;
            }

            $mainEntity[] = Schema::question()
                ->name(trim($faq['question']))
                ->acceptedAnswer(
                    Schema::answer()
                        ->text(trim($faq['answer']))
                );
        }

        if (empty($mainEntity)) {
            return '';
        }

        $schema->mainEntity($mainEntity);
        return $schema->toScript();
    }

    private function generateCategoryBreadcrumb($category)
    {
        return sprintf(
            '<a style="color:#E62A16;" href="%s">Home</a> / ' .
            '<a style="color:#E62A16;" href="%s">Box By Industry</a> / ' .
            '<a style="color:#757474;" href="#">%s</a>',
            route('index'),
            route('allcategories'),
            e($category->name)
        );
    }

    private function generateSubcategoryBreadcrumb($subCategory)
    {
        return sprintf(
            '<a style="color:#E62A16;" href="%s">Home</a> / ' .
            '<a style="color:#E62A16;" href="%s">Box By Industry</a> / ' .
            '<a style="color:#E62A16;" href="%s">%s</a> / ' .
            '<a style="color:#757474;" href="#">%s</a>',
            route('index'),
            route('allcategories'),
            route('page', $subCategory->procategories->slug),
            e($subCategory->procategories->name),
            e($subCategory->name)
        );
    }

    private function generateProductBreadcrumb($product,$mainCategory)
    {
        return sprintf(
            '<a style="color:#E62A16;" href="%s">Home</a> / ' .
            '<a style="color:#E62A16;" href="%s">Box By Industry</a> / ' .
            '<a style="color:#E62A16;" href="%s">%s</a> / ' .
            '<a style="color:#E62A16;" href="%s">%s</a> / ' .
            '<a style="color:#757474;" href="#">%s</a>',
            route('index'),
            route('allcategories'),
            route('page', $mainCategory->slug),
            e($mainCategory->name),
            route('page', $product->category[0]->slug),
            e($product->category[0]->name),
            e($product->product_name)
        );
    }
}
