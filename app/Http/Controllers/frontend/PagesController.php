<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\OrderStoreRequest;
use App\Http\Requests\GetQuoteRequest;
use App\Jobs\SendQuoteEmailJob;
use App\Mail\OrderMail;
use App\Mail\QuoteMail;
use App\Mail\PriceMatchMail;
use App\Models\NewsLetter;
use App\Models\Blog;
use App\Models\Category;
use App\Models\CbdCategory;
use App\Models\CbdProduct;
use App\Models\PriceMatch;
use App\Models\Contact;
use App\Models\Order;
use App\Models\Product;
use App\Models\Quote;
use App\Models\User;
use App\Services\CartService;
use App\Services\GoogleReviewsService;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\TwitterCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use GuzzleHttp\Client;
use Session;
use Carbon\Carbon;
use Spatie\SchemaOrg\Schema;
use Spatie\SchemaOrg\Graph;
use Spatie\SchemaOrg\MultiTypedEntity;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use DateTime;

class PagesController extends Controller
{

    public function sitemap()
    {
        try {
            // Add dynamic URLs for pages, products, categories, blogs
            $this->addDynamicUrls();
            // Generate and save the image sitemap
            $this->generateImageSitemap();
            $this->generateGoogleMerchantFeed();
            $this->generateGoogleMerchantLocalFeed();

            return view('frontend.pages.sitemap');
        } catch (\Exception $e) {
            Log::error('Sitemap generation failed: ' . $e->getMessage());
            throw $e; // Re-throw to see the error in development
        }
    }

    private function addDynamicUrls()
    {
        // Generate the main sitemap
        $sitemap = Sitemap::create();
        $sitemap->add(Url::create(route('index'))->setLastModificationDate(Carbon::today())->setChangeFrequency(Url::CHANGE_FREQUENCY_ALWAYS))
            ->add(Url::create(route('thankyou'))->setLastModificationDate(Carbon::today())->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY))
            ->add(Url::create(route('blog.page'))->setLastModificationDate(Carbon::today())->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY))
            ->add(Url::create(route('aboutUs'))->setLastModificationDate(Carbon::today())->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY))
            ->add(Url::create(route('SecurityAndPrivacy'))->setLastModificationDate(Carbon::today())->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY))
            ->add(Url::create(route('termsAndCondition'))->setLastModificationDate(Carbon::today())->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY))
            ->add(Url::create(route('contactUs'))->setLastModificationDate(Carbon::today())->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY))
            ->add(Url::create(route('allproducts'))->setLastModificationDate(Carbon::today())->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY))
            ->add(Url::create(route('faq'))->setLastModificationDate(Carbon::today())->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY))
            ->add(Url::create(route('search'))->setLastModificationDate(Carbon::today())->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY))
            ->add(Url::create(route('allcategories'))->setLastModificationDate(Carbon::today())->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY))
            ->add(Url::create(route('refundPolicy'))->setLastModificationDate(Carbon::today())->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY))
            ->add(Url::create(route('shippingPolicy'))->setLastModificationDate(Carbon::today())->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));

        // Add products
        $items = Product::active()->select(['slug', 'updated_at'])->get();
        foreach ($items as $item) {
            $sitemap->add(
                Url::create(route('page', $item->slug))
                    ->setLastModificationDate($this->lastModifiedDate($item->updated_at))
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            );
        }

        // Add categories
        $items = Category::active()->select(['slug', 'updated_at'])->get();
        foreach ($items as $item) {
            $sitemap->add(
                Url::create(route('page', $item->slug))
                    ->setLastModificationDate($this->lastModifiedDate($item->updated_at))
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            );
        }

        // Add Blogs
        $items = Blog::active()->select(['slug', 'updated_at'])->get();
        foreach ($items as $item) {
            $sitemap->add(
                Url::create(route('blog.detail', $item->slug))
                    ->setLastModificationDate($this->lastModifiedDate($item->updated_at))
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            );
        }

        $sitemap->writeToFile(public_path('sitemap.xml'));
    }

    private function lastModifiedDate($value): DateTime
    {
        return new DateTime($value ?: 'now');
    }

    private function generateImageSitemap()
    {
        // Create a new sitemap instance
        $sitemap = Sitemap::create();

        // Fetch products with their associated media (images)
        $items = Product::with('media')->active()->select(['slug', 'updated_at', 'id', 'product_title'])->get();

        // Start building the XML content
        $xmlContent = '<?xml version="1.0" encoding="UTF-8"?>
        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

        // Loop through each product and add its images to the sitemap
        foreach ($items as $item) {
            // Convert updated_at to Carbon instance if it's not already
            $lastmod = $item->updated_at instanceof Carbon
                ? $item->updated_at
                : Carbon::parse($item->updated_at);

            $xmlContent .= '
               <url>
               <loc>' . htmlspecialchars(route('page', $item->slug)) . '</loc>
               <lastmod>' . $lastmod->format('Y-m-d\TH:i:sP') . '</lastmod>
               <priority>0.8</priority>
               <changefreq>monthly</changefreq>';
            // Add images for the product
            foreach ($item->media as $media) {
                $xmlContent .= '
             <image:image>
             <image:loc>' . htmlspecialchars($media->getUrl()) . '</image:loc>
             <image:title>' . htmlspecialchars($item->product_title) . '</image:title>
              </image:image>';
            }
            $xmlContent .= '
              </url>';
        }
        // Close the XML content
        $xmlContent .= '
         </urlset>';

        // Save the image sitemap to the public directory
        \File::put(public_path('images-sitemap.xml'), $xmlContent);
    }


    private function generateGoogleMerchantFeed()
    {
        $xmlContent = '<?xml version="1.0" encoding="UTF-8"?>
         <rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">
         <channel>
            <title>' . htmlspecialchars(sys_settings('site_meta_title', config('app.name'))) . '</title>
            <link>' . htmlspecialchars(config('app.url')) . '</link>
            <description>' . htmlspecialchars('Merchant feed disabled until accurate fixed product prices are available on matching landing pages.') . '</description>
          </channel>
         </rss>';

        \File::put(public_path('google-merchant-feed.xml'), $xmlContent);
    }

    private function generateGoogleMerchantLocalFeed()
    {
        $xmlContent = '<?xml version="1.0" encoding="UTF-8"?>
         <rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">
           <channel>
          ' . '<title>' . htmlspecialchars(config('app.name') . ' | Local Inventory') . '</title>
            <link>' . htmlspecialchars(config('app.url')) . '</link>
             <description>' . htmlspecialchars('Local inventory feed disabled until accurate local inventory prices are available.') . '</description>
    </channel>
</rss>';

        \File::put(public_path('google-merchant-local-feed.xml'), $xmlContent);
    }

    public function storageLink()
    {
        dd('tets');
    }


    public function OptimizeClear()
    {
        Artisan::call('optimize:clear');
        return response()->json(['status' => 'Optimize cleared successfully']);
    }

       private function seoMetaData($title,$description,$keywords,$url,$image=null,$meta=null): void

    {
        // Basic SEO
        SEOTools::setTitle($title);
        SEOTools::setDescription($description);
        SEOTools::setCanonical($url);

        // OpenGraph
        SEOTools::opengraph()->setTitle($title);
        SEOTools::opengraph()->setDescription($description);
        SEOTools::opengraph()->setSiteName(config('app.name'));
        SEOTools::opengraph()->setUrl($url);
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
        SEOTools::metatags()->addMeta('robots', $meta === null ? 'index, follow' : 'noindex, nofollow');
        SEOTools::metatags()->setKeywords([
            $keywords
        ]);
    }

    public function AllCategories()
    {
        $this->seoMetaData(
            'Box By Industries',
            sys_settings('site_meta_description'),
            sys_settings('meta_site_keywords'),
            route('allcategories')
        );

        $categories = Category::Where('parent_id', '=', '0')->get();

        // for list schema
        $itemList = Schema::itemList();
        $itemListElements = [];
        $itemListElements[] = Schema::listItem()
            ->position(1)
            ->name('Home')
            ->item(route('index'));

        $itemListElements[] = Schema::listItem()
            ->position(2)
            ->name('Box by industry')
            ->item(route('allcategories'));

        foreach ($categories as $index => $category) {
            $itemListElements[] = Schema::listItem()
                ->position($index + 3)
                ->name($category->title)
                ->item(route('page', $category->slug));
        }

        $itemList->itemListElement($itemListElements);
        $data['listSchema'] = $itemList->toScript();


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
                ]
            ]
        ];

        $data['breadcrumbScript']  = '<script type="application/ld+json">' . json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';

        // for webpage schema
          $data['schemaWebPage']  = Schema::webPage()
            ->name('Box By Industry')
            ->description(sys_settings('meta_site_description'))
            ->url(route('index'))
            ->identifier(route('index') . "#webpage")
            ->inLanguage('en-US');


        $AllCategoriesBreadcrumb = "  <a  style='color:#E62A16;' href='" . route('index') . "'>Home</a> / <a style='color:#757474;' href=" . route('allcategories') . ">Box By Industry</a>";
        return view('frontend.pages.all-categories', compact('AllCategoriesBreadcrumb','categories','data'));
    }
    public function page($slug, $pagePrefix = null, $pageNumber = null)
    {
        try {
            // Validate route parameters
            if ($pagePrefix && !$pageNumber) {
                abort(404);
            }

            // Check for category to subcategory
            if ($categoryToSubCategory = $this->categoryToSubCategory($slug)) {

                foreach($categoryToSubCategory->media as $media) {
                    $image = $media->getUrl();
                }
                //for SEO meta tags
                $this->seoMetaData(
                    $categoryToSubCategory->meta_title ?? $categoryToSubCategory->title,
                    $categoryToSubCategory->meta_description,
                    $categoryToSubCategory->meta_keywords ?? sys_settings('meta_site_keywords'),
                    route('page',$categoryToSubCategory->slug),
                    $image
                );

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

                $AllCategoriesBreadcrumb = "  <a  style='color:#E62A16;' href='" . route('index') . "'>Home</a> / <a  style='color:#E62A16;' href=" . route('allcategories') . ">Box By Industry</a> / <a style='color:#757474;' href=" . " >" . $categoryToSubCategory->name . "</a>";
                return view('frontend.pages.category-to-subCategory', compact('categoryToSubCategory', 'AllCategoriesBreadcrumb','itemList','breadcrumbScript','schemaWebPage'));
            }

            // Check for subcategory to products
            if ($subCategoryToProducts = $this->subCategoryToProducts($slug)) {

                foreach($subCategoryToProducts->media as $media) {
                    $image = $media->getUrl();
                }
                $this->seoMetaData(
                    $subCategoryToProducts->meta_title ?? $subCategoryToProducts->title,
                    $subCategoryToProducts->meta_description,
                    $subCategoryToProducts->meta_keywords ?? sys_settings('meta_site_keywords'),
                    route('page',$subCategoryToProducts->slug),
                    $image
                );

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

                $subCategoryToProductsBreadcrumb = "  <a  style='color:#E62A16;' href='" . route('index') . "'>Home</a> / <a style='color:#E62A16;' href=" . route('allcategories') . ">Box By Industry</a> / <a style='color:#E62A16;' href=" . route('page', $subCategoryToProducts->procategories->slug) . "> " . $subCategoryToProducts->procategories->name . "</a> / <a style='color:#757474;' href=" . ">" . $subCategoryToProducts->name . "</a>";
                return view('frontend.pages.subCategory-to-products', compact('subCategoryToProducts', 'subCategoryToProductsBreadcrumb','breadcrumbScript','itemList','schemaWebPage'));
            }

            // Check for product
            if ($product = $this->products($slug)) {

                foreach($product->media as $media) {
                    $image = $media->getUrl();
                }
                $this->seoMetaData(
                    $product->meta_title ?? $product->product_title,
                    $product->meta_description,
                    $product->meta_keywords ?? sys_settings('meta_site_keywords'),
                    route('page',$product->slug),
                    $image
                );

                // Ensure product has categories
                    if (!$product->category || !count($product->category)) {
                        abort(404);
                    }

                    $main_category = Category::where('id', $product->category[0]->parent_id)->first();
                    if (!$main_category) {
                        abort(404);
                    }

                    // Safely decode JSON data
                    $related_pro = json_decode($product->related_products) ?: [];
                    $productFaqs = json_decode($product->faqs, true) ?: [];

                    // Get related products if any exist
                    $relatedProducts = !empty($related_pro)
                        ? Product::whereIn('id', $related_pro)->get()
                        : collect([]);

                    $productBreadcrumb = $this->generateProductBreadcrumb($product, $main_category);


                // for webpage schema
                $data['schemaWebPage'] = Schema::webPage()
                    ->name($product->product_title)
                    ->description($product->mata_description)
                    ->url(route('index'))
                    ->identifier(route('index') . "#webpage")
                    ->datePublished($product->created_at)
                    ->dateModified($product->updated_at)
                    ->inLanguage('en-US');

                    // product FAQs schema
                    $data['faqSchema'] = '';
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
                            $data['faqSchema'] = $schema->toScript();
                        }
                    }

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
                            "name" => $main_category->name,
                            "item" => route('page', $main_category->slug)
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
                $data['breadcrumbSchema'] = '<script type="application/ld+json">' . json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';

               return view('frontend.pages.product-detail', compact(
                            'product',
                            'relatedProducts',
                            'productBreadcrumb',
                            'productFaqs',
                            'data'
                        ));

                    // If nothing found, return 404
                    abort(404);
        }
        } catch
        (\Exception $e) {
            Log::error('Error in page method: ' . $e->getMessage());
            abort(404);
        }
      }

    /**
     * Generate product breadcrumb HTML
     */
    private function generateProductBreadcrumb($product, $main_category): string
    {
        return sprintf(
            '<a style="color:#E62A16;" href="%s">Home</a> / ' .
            '<a style="color:#E62A16;" href="%s">Box By Industry</a> / ' .
            '<a style="color:#E62A16;" href="%s">%s</a> / ' .
            '<a style="color:#E62A16;" href="%s">%s</a> / ' .
            '<a style="color:#757474;" href="%s">%s</a>',
            route('index'),
            route('allcategories'),
            route('page', $main_category->slug),
            e($main_category->name),
            route('page', $product->category[0]->slug),
            e($product->category[0]->name),
            route('page', $product->slug),
            e($product->product_name)
        );
    }

    private function render($page, $data)
    {
        //  dd($page,$data);
        return view("website.$page", $data);
    }


    public function categoryToSubCategory($slug)
    {
        return Category::isparent($slug)->with('categories', 'media')->first();
    }

    public function subCategoryToProducts($slug)
    {

        return Category::notparent($slug)->with('procategories', 'products', 'media')->first();
    }

    public function products($slug)
    {
        return Product::where('slug', $slug)->active()->with('category', 'media')->first();
    }



    /**
     * Browse every published box, grouped by category.
     */
    public function AllProducts()
    {
        $this->seoMetaData(
            'All Custom Packaging Boxes - ' . config('app.name'),
            sys_settings('site_meta_description'),
            sys_settings('meta_site_keywords'),
            route('allproducts')
        );

        $breadcrumb = sprintf(
            '<a style="color:#E62A16;" href="%s">Home</a> / <a style="color:#757474;" href="#">All Products</a>',
            route('index')
        );

        return view('frontend.pages.all-products', ['AllProductBreadcrumb' => $breadcrumb]);
    }




    public function ThankYou()
    {
        $this->seoMetaData(
            sys_settings('site_meta_title'),
            sys_settings('site_meta_description'),
            sys_settings('meta_site_keywords'),
            route('thankyou')
        );
        return view('frontend.pages.thankyou');
    }



   public function BlogPage()
    {
        $this->seoMetaData(
            sys_settings('blogs_meta_title'),
            sys_settings('blogs_meta_description'),
            sys_settings('meta_site_keywords'),
            route('blog.page')
        );
        $blogs = Blog::latest()->get();

        // Create Blog Schema
        $blogSchema = Schema::blog()
            ->name(sys_settings('blogs_meta_title'))
            ->description(sys_settings('blogs_meta_description'))
            ->url(route('blog.page'))
            ->publisher(
                Schema::organization()
                    ->name(config('app.name'))
                    ->logo(asset('logo.svg'))
            );

        // Create Blog Posting Schema for each blog
        $blogPosts = [];
        foreach ($blogs as $blog) {
            $blogPosts[] = Schema::blogPosting()
                ->headline($blog->title)
                ->description($blog->meta_description)
                ->articleBody($blog->meta_description)
                ->url(route('blog.detail', $blog->slug))
                ->image(asset('blogs/'.$blog->blog_image))
                ->datePublished($blog->created_at ? $blog->created_at->toIso8601String() : now()->toIso8601String())
                ->dateModified($blog->updated_at ? $blog->updated_at->toIso8601String() : now()->toIso8601String())
                ->author(
                    Schema::person()
                        ->name(config('app.name'))
                );
        }

        $blogSchema->blogPost($blogPosts)->toScript();

        return view('frontend.pages.blogs', compact('blogs','blogSchema'));
    }

    public function BlogDetail($slug)
    {

        $blog = Blog::where('slug', $slug)->first();

        if (!$blog) {
            abort(404, 'Blog post not found');
        }

        $recent_blog = Blog::latest()->take(10)->get();

        $this->seoMetaData(
            $blog->title,
            $blog->meta_description,
            sys_settings('meta_site_keywords'),
            route('blog.detail', $blog->slug),
            asset('blogs/'.$blog->blog_image)
        );

        // Create Blog Post Schema
        $blogSchema = Schema::blogPosting()
            ->headline($blog->title)
            ->description($blog->meta_description)
            ->articleBody($blog->meta_description)
            ->url(route('blog.page', $blog->slug))
            ->image(asset('blogs/'.$blog->blog_image))
            ->datePublished($blog->created_at ? $blog->created_at->toIso8601String() : now()->toIso8601String())
            ->dateModified($blog->updated_at ? $blog->updated_at->toIso8601String() : now()->toIso8601String())
            ->author(
                Schema::person()
                    ->name( config('app.name'))
            )
            ->publisher(
                Schema::organization()
                    ->name(config('app.name'))
                    ->logo(asset('logo.svg'))
            );
        $blogSchema->toScript();


        return view('frontend.pages.blog-detail', compact('blog', 'recent_blog','blogSchema'));

    }

    public function AboutUs()
    {

        $this->seoMetaData(
            sys_settings('about_meta_title'),
            sys_settings('about_meta_description'),
            sys_settings('meta_site_keywords'),
            route('aboutUs')
        );
        return view('frontend.pages.about-us');
    }

    public function SecurityAndPrivacy()
    {
        $this->seoMetaData(
            sys_settings('policy_meta_title'),
            sys_settings('policy_meta_description'),
            sys_settings('meta_site_keywords'),
            route('SecurityAndPrivacy')
        );
        return view('frontend.pages.privacy-policy');
    }

    public function TermsAndCondition()
    {
        $this->seoMetaData(
            sys_settings('tnc_meta_title'),
            sys_settings('tnc_meta_description'),
            sys_settings('meta_site_keywords'),
            route('termsAndCondition')
        );
        return view('frontend.pages.terms-of-services');
    }

    public function refundPolicy()
    {
        $this->seoMetaData(
            sys_settings('refund_policy_meta_title'),
            sys_settings('refund_policy_meta_description'),
            sys_settings('meta_site_keywords'),
            route('refundPolicy')
        );
        return view('frontend.pages.refund-policy');
    }

    public function ReturnPolicy()
    {
        return redirect()->route('refundPolicy', status: 301);
    }

    public function ShippingPolicy()
    {
        $this->seoMetaData(
            sys_settings('shipping_policy_meta_title', 'Shipping Policy | ' . config('app.name')),
            sys_settings('shipping_policy_meta_description', 'Shipping timelines, production handling, delivery areas, and freight notes for custom packaging orders.'),
            sys_settings('meta_site_keywords'),
            route('shippingPolicy')
        );
        return view('frontend.pages.shipping-policy');
    }

    public function ContactUs()
    {
        $this->seoMetaData(
            sys_settings('contact_meta_title'),
            sys_settings('contact_meta_description'),
            sys_settings('meta_site_keywords'),
            route('contactUs')
        );
        return view('frontend.pages.contact-us');
    }

    public function FAQ()
    {
        $this->seoMetaData(
            sys_settings('faqs_meta_title'),
            sys_settings('faqs_meta_description'),
            sys_settings('meta_site_keywords'),
            route('faq')
        );
        return view('frontend.pages.faq');
    }



// not releated to this site

    public function SubCategory($slug)
    {
        $procategories = Category::Where('slug', $slug)->with('categories')->first();

        // category FAQs schema
        $categoryFaqs = json_decode($procategories->faqs, true);
        $data['faqSchema'] = '';
        if (json_last_error() === JSON_ERROR_NONE && is_array($categoryFaqs) && count($categoryFaqs) > 0) {
            $schema = Schema::fAQPage()
                ->identifier(route('faq'))
                ->name('Frequently Asked Questions')
                ->description('Have a question in mind? Navigate through these questions to find answers. However, if you cannot see your question in the list, feel free to drop a call.')
                ->setProperty('@id', request()->url() . '#faq');
            $mainEntity = [];
            foreach ($categoryFaqs as $faq) {
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
                $data['faqSchema'] = $schema->toScript();
            }
        }


        $AllCategoriesBreadcrumb = "  <a  style='color:#E62A16;' href='" . route('index') . "'>Home</a> / <a  style='color:#E62A16;' href=" . route('allcategories') . ">Box By Industry</a> / <a style='color:#757474;' href=" . " >" . $procategories->name . "</a>";

        return view('frontend.pages.subcategory', compact('procategories', 'AllCategoriesBreadcrumb', 'data'));
    }

    public function ProductByCategory($categorySlug, $subcategorySlug)
    {

        $category = Category::where('slug', $subcategorySlug)->first();

        $products = $category->products()->paginate(5);

        // category FAQs schema
        $categoryFaqs = json_decode($category->faqs, true);
        $data['faqSchema'] = '';
        if (json_last_error() === JSON_ERROR_NONE && is_array($categoryFaqs) && count($categoryFaqs) > 0) {
            $schema = Schema::fAQPage()
                ->identifier(route('faq'))
                ->name('Frequently Asked Questions')
                ->description('Have a question in mind? Navigate through these questions to find answers. However, if you cannot see your question in the list, feel free to drop a call.')
                ->setProperty('@id', request()->url() . '#faq');
            $mainEntity = [];
            foreach ($categoryFaqs as $faq) {
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
                $data['faqSchema'] = $schema->toScript();
            }
        }


        $CategoryBreadcrumb = "  <a  style='color:#E62A16;' href='" . route('index') . "'>Home</a> / <a style='color:#E62A16;' href=" . route('allcategories') . ">Box By Industry</a> / <a style='color:#E62A16;' href=" . route('sub.category', $category->procategories->slug) . "> " . $category->procategories->name . "</a> / <a style='color:#757474;' href=" . ">" . $category->name . "</a>";
        return view('frontend.pages.productsbycategory-page', compact('category', 'products', 'CategoryBreadcrumb'));
    }


    public function ProductDetail($slug)
    {

        $product = Product::where('slug', $slug)->with('category', 'media')->first();
        if ($product) {
            $main_category = Category::where('id', $product->category[0]->parent_id)->first();
            $images = json_decode($product->product_multiple_images);
            $related_pro = json_decode($product->related_products);
            $faqs = json_decode($product->faqs, true);
            if (!empty($related_pro)) {
                $related_products = Product::whereIn('id', $related_pro)->get();
            } else {
                $related_products = '';
            }
            $AllProductsBreadcrumb = " <a style='color:#E62A16;' href='" . route('index') . "'> Home</a> / <a style='color:#E62A16;' href=" . route('allcategories') . ">Box By Industry</a> / <a style='color:#E62A16;' href=" . route('sub.category', $main_category->slug) . ">" . $main_category->name . "</a> / <a style='color:#E62A16;' href=" . route('product.by.category', ['categorySlug' => $main_category->slug, 'subcategorySlug' => $product->category[0]->slug]) . ">" . $product->category[0]->name . "</a> / <a style='color:#757474;' href=" . route('product.detail', $product->slug) . ">" . $product->product_name . "</a>";

            // Load Google Reviews data
            $googleReviewsService = new GoogleReviewsService();
            $googleReviews = $googleReviewsService->getReviews(30);
            $overallRating = $googleReviewsService->getOverallRating();
            $totalReviews = $googleReviewsService->getTotalReviews();

            // product FAQs schema
            $productFaqs = json_decode($product->faqs, true);
            if (is_array($productFaqs) && count($productFaqs) > 0) {
                $schema = Schema::fAQPage()
                    ->identifier(route('faq'))
                    ->name('Frequently Asked Questions')
                    ->description('Have a question in mind? Navigate through these questions to find answers. However, if you cannot see your question in the list, feel free to drop a call.');

                // Add individual FAQs to the schema
                $mainEntity = [];
                foreach ($productFaqs as $faq) {
                    $mainEntity[] = Schema::question()
                        ->name($faq['question']) // Assuming you have a 'question' field
                        ->acceptedAnswer(
                            Schema::answer()
                                ->text($faq['answer']) // Assuming you have an 'answer' field
                        );
                }
                $schema->mainEntity($mainEntity);
                // Convert the schema to JSON-LD format
                $data['faqSchema'] = $schema->toScript();
            }



            return view('frontend.pages.product-detail', compact('product', 'images', 'related_products', 'AllProductsBreadcrumb', 'faqs', 'googleReviews', 'overallRating', 'totalReviews'));
        }
        return abort(404);

    }

    public function QuoteUseAsCart()
    {

        $this->seoMetaData(
            sys_settings('site_meta_title'),
            sys_settings('site_meta_description'),
            sys_settings('meta_site_keywords'),
            route('cart.quote'),
            null,
            'noindex, nofollow'
        );
        return view('frontend.pages.quote-page');
    }

    public function Offer()
    {
        return view('frontend.pages.offer');
    }
    public function StoreOrder(OrderStoreRequest $request)
    {
        $order = new Order();
        $order->name = isset($request->name) ? $request->name : '';
        $order->email = $request->email;
        $order->phone = $request->phone;
        $order->length = isset($request->length) ? $request->length : '';
        $order->depth = isset($request->depth) ? $request->depth : '';
        $order->width = isset($request->width) ? $request->width : '';
        $order->colors = isset($request->colors) ? $request->colors : '';
        $order->units = isset($request->units) ? $request->units : '';
        $order->quantity = isset($request->quantity) ? $request->quantity : '';
        $order->quantity2 = isset($request->quantity2) ? $request->quantity2 : '';
        $order->quantity3 = isset($request->quantity3) ? $request->quantity3 : '';
        $order->stock = isset($request->stock) ? $request->stock : '';
        $order->notes = isset($request->notes) ? $request->notes : '';
        $order->box_style = isset($request->box_style) ? $request->box_style : '';
        $order->product_image = isset($request->product_front_image) ? $request->product_front_image : '';
        $order->product_name = isset($request->product_name) ? $request->product_name : '';
        $order->product_title = isset($request->product_title) ? $request->product_title : '';
        $order->order_status = 1;

        if ($request->hasFile('art_desgin')) {
            $image = $request->file('art_desgin');
            $extension = $image->getClientOriginalExtension();
            $filename = rand(111, 99999) . '.' . $extension;
            $image_path = 'frontend/banner/' . $filename;
            Image::make($image)->resize(300, 300)->save($image_path);
            $order->art_desgin = $filename;
        }
        if ($request->otherproduct == null) {
            $otherproduct = [];

        } else {

            $otherproduct = $request->otherproduct;
            $products_data = [];
            foreach ($otherproduct as $pro) {
                $products_data[] = $pro;
            }


            $order->otherproduct = json_encode($products_data);
        }


        $token = $request->input('g-recaptcha-response');

        if ($token) {
            $client = new Client();
            $response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
                'form_params' => array(
                    'secret'   => sys_settings('recaptcha_secret_key'),
                    'response' => $token
                )
            ]);
            $results = json_decode($response->getBody()->getContents());

            if ($results->success) {

                $order->save();
                try {
                    Mail::to('quote@cusotmboxeszone.com')->send(new OrderMail($order));
                } catch (\Exception $exception) {
                    Log::error($exception->getMessage());
                }
                return redirect()->route('thankyou')->with('success', 'Quote Send Successfully');

            } else {
                return redirect()->back()->with('error', 'Please Check ReCaptcha');
            }

        } else {
            return redirect()->back()->with('error', 'Please Check ReCaptcha');
        }

    }
    public function StoreContactUs(GetQuoteRequest $request)
    {

        $quote = new Quote();
        $quote->name = $request->name;
        $quote->phone = $request->phone;
        $quote->email = $request->email;
        $quote->specification = $request->specification;
        $token = $request->input('g-recaptcha-response');
        if ($token) {
            $client = new Client();
            $response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
                'form_params' => array(
                    'secret'   => sys_settings('recaptcha_secret_key'),
                    'response' => $token
                )
            ]);
            $results = json_decode($response->getBody()->getContents());

            if ($results->success) {

                $quote->save();
                try {

                    dispatch(new SendQuoteEmailJob($quote));

                } catch (\Exception $exception) {
                    Log::error($exception->getMessage());
                }
                return redirect()->route('thankyou')->with('success', 'Your Quote Sent Successfully Our Representative Will Connect You Soon Thank You...');


            } else {
                return redirect()->back()->with('error', 'Please Check ReCaptcha');
            }

        } else {
            return redirect()->back()->with('error', 'Please Check ReCaptcha');
        }

    }

    public function StoreNewsLetter(Request $request)
    {

        if ($request->ajax()) {


            //  dd($request->sub_email);
            $sub_count = NewsLetter::where('email', $request->sub_email)->count();

            if ($sub_count > 0) {

                echo "exits";
            } else {

                $newsletter = new NewsLetter();
                $newsletter->email = $request->sub_email;
                $newsletter->status = 1;
                // dd($newsletter);
                $newsletter->save();
                echo "saved";

            }

        }

    }


    public function priceMatch()
    {

        return view('frontend.pages.price-match');
    }
    public function SavePriceMatchData(Request $request)
    {

        $price_match = new PriceMatch();
        $price_match->name = isset($request->f_name) ? $request->f_name : '';
        $price_match->l_name = isset($request->l_name) ? $request->l_name : '';
        $price_match->email = isset($request->email) ? $request->email : '';
        $price_match->phone = isset($request->phone) ? $request->phone : '';
        $price_match->company = isset($request->company) ? $request->company : '';
        $price_match->country = isset($request->country) ? $request->country : '';
        $price_match->message = isset($request->message) ? $request->message : '';

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $filename = rand(111, 99999) . '.' . $extension;
            $image_path = 'frontend/pricematch/' . $filename;
            Image::make($image)->resize(500, 500)->save($image_path);
            $price_match->image = $filename;
        }

        if ($request->info == null) {
            $info = [];
        } else {
            $info = $request->info;
            $info_data = [];
            foreach ($info as $pro) {
                $info_data[] = $pro;
            }
            $price_match->info = json_encode($info_data);
        }

        $token = $request->input('g-recaptcha-response');
        if ($token) {
            $price_match->save();
            //   \Session::put('price', $price_match);


            try {

                Mail::to('quote@cusotmboxeszone.com')->send(new PriceMatchMail($price_match));
            } catch (\Exception $exception) {

                Log::error($exception->getMessage());
            }

            return redirect()->route('thankyou');

        } else {
            return redirect()->back()->with('success', 'Please Check the ReCaptcha');

        }
    }

    public function QuickView(Request $request)
    {
        $product_id = $request->id;
        $productModel = Product::where('id', $product_id)->first();
        $images = json_decode($productModel->product_multiple_images);
        return view('frontend/includes/productdetailModel', compact('productModel', 'images'));


    }

    public function GetQuote()
    {
        // Get categories for the dropdown menus using the correct column names
        $byIndustries = Category::where('by_industry', 1)->where('status', 1)->get();
        $byMaterials = Category::where('by_material', 1)->where('status', 1)->get();
        $byStyles = Category::where('by_style', 1)->where('status', 1)->get();

        return view('frontend.pages.get-quote', compact('byIndustries', 'byMaterials', 'byStyles'));
    }

    public function GetQuotepage()
    {

        $this->seoMetaData(
            sys_settings('site_meta_title'),
            sys_settings('site_meta_description'),
            sys_settings('meta_site_keywords'),
            route('cart.quote'),
            null,
            'noindex, nofollow'
        );
        return view('frontend.pages.get-quote-page');
    }

    /**
     * Cart - review the boxes configured on the product pages.
     */
    public function CartPage()
    {
        $this->seoMetaData(
            'Your Cart - ' . config('app.name'),
            'Review the custom boxes you have configured before requesting your quote.',
            sys_settings('meta_site_keywords'),
            route('cart.quote'),
            null,
            'noindex, nofollow'
        );

        $breadcrumb = sprintf(
            '<a style="color:#E62A16;" href="%s">Home</a> / <a style="color:#757474;" href="#">Cart</a>',
            route('index')
        );

        return view('frontend.pages.cart', ['CartBreadcrumb' => $breadcrumb]);
    }

    /**
     * Checkout - contact details for the order. An empty cart has nothing to
     * check out, so send the customer back to the cart.
     */
    public function CheckoutPage(CartService $cart)
    {
        if ($cart->isEmpty()) {
            return redirect()->route('cart.quote');
        }

        $this->seoMetaData(
            'Checkout - ' . config('app.name'),
            'Confirm your details and submit your custom packaging order.',
            sys_settings('meta_site_keywords'),
            route('checkout.page'),
            null,
            'noindex, nofollow'
        );

        $breadcrumb = sprintf(
            '<a style="color:#E62A16;" href="%s">Home</a> / ' .
            '<a style="color:#E62A16;" href="%s">Cart</a> / ' .
            '<a style="color:#757474;" href="#">Checkout</a>',
            route('index'),
            route('cart.quote')
        );

        return view('frontend.pages.checkout', ['CheckoutBreadcrumb' => $breadcrumb]);
    }


}
