<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Backend\bannerController;
use App\Http\Controllers\Backend\BlogController;
use App\Http\Controllers\Backend\CampaignController;
use App\Http\Controllers\Backend\VariationQuoteController;
use App\Http\Controllers\Backend\CbdController;
use App\Http\Controllers\Backend\ContactController;
use App\Http\Controllers\Backend\HomeController;
use App\Http\Controllers\Backend\MarketingController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\ProductVariationController;
use App\Http\Controllers\Backend\PriceMatchController;
use App\Http\Controllers\Backend\ProductCategoryController;
use App\Http\Controllers\Backend\ProductManagementController;
use App\Http\Controllers\Backend\QueryController;
use App\Http\Controllers\Backend\TestimonialController;
use App\Http\Controllers\Backend\UserManagement\PermissionsController;
use App\Http\Controllers\Backend\UserManagement\RolesController;
use App\Http\Controllers\Backend\UserManagement\UsersController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Admin\GoogleReviewController;
use App\Http\Controllers\Admin\GoogleReviewsScraperController;
use App\Http\Controllers\Admin\GoogleMapsDataController;
use App\Http\Controllers\Backend\CbdCategoryController;
use App\Http\Controllers\frontend\indexController;
use App\Http\Controllers\Backend\LogoController;
use App\Http\Controllers\Backend\NewsLetterController;
use App\Http\Controllers\frontend\PagesController;
use App\Http\Controllers\Backend\InvoiceManagementController;
use App\Http\Livewire\PageRenderer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\HomeController as Home;
use App\Http\Controllers\QuillImageController;
use App\Http\Controllers\EditorImageController;
use App\Http\Controllers\Backend\CartOrderController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

Auth::routes();

// Generic redirect routes - just add slug mappings to the array
$redirects = [
    'cutom-pencil-boxes' => 'custom-pencil-boxes',
    'premium-packaging-usa' => 'custom-packaging-boxes-usa',
];

foreach ($redirects as $from => $to) {
    Route::get('/' . $from, function () use ($to) {
        return redirect('/' . $to, 301);
    });
}


// Temporary route to fix cache and storage issues - REMOVE AFTER USE
Route::get('/run/cache-command', function () {
    try {
        $results = [];

        // Clear response cache FIRST - this is crucial for images!
        if (app()->bound('responsecache')) {
            app('responsecache')->clear();
            $results[] = 'Response cache cleared ✓ (Cached HTML pages removed)';
        }

        // Clear general caches
        Artisan::call('config:clear');
        $results[] = 'config:clear ✓';

        Artisan::call('cache:clear');
        $results[] = 'cache:clear ✓';

        Artisan::call('route:clear');
        $results[] = 'route:clear ✓';

        Artisan::call('view:clear');
        $results[] = 'view:clear ✓';

        // Clear specific homepage caches - IMPORTANT FOR IMAGES
        Cache::forget('homepage_products_with_category');
        $results[] = 'homepage_products_with_category cache cleared ✓';

        Cache::forget('homepage_subcategories');
        $results[] = 'homepage_subcategories cache cleared ✓';

        Cache::forget('homepage_blogs');
        $results[] = 'homepage_blogs cache cleared ✓';

        Cache::forget('menu_categories');
        $results[] = 'menu_categories cache cleared ✓';

        Cache::forget('homepage-categories');
        $results[] = 'homepage-categories cache cleared ✓';

        // Create storage link - THIS IS THE KEY FIX FOR IMAGES
        try {
            Artisan::call('storage:link');
            $results[] = 'storage:link ✓ (Images should now work)';
        } catch (\Exception $e) {
            $results[] = 'storage:link - ' . $e->getMessage();
        }

        // Recache config
        Artisan::call('config:cache');
        $results[] = 'config:cache ✓';

        return response()->json([
            'status' => 'success',
            'message' => 'All fixes applied - Response cache cleared! Images should now display',
            'results' => $results,
            'note' => 'If images still do not show, hard refresh your browser (Ctrl+Shift+R or Cmd+Shift+R)'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to apply fixes',
            'error' => $e->getMessage()
        ], 500);
    }
})->name('fix.cache');



Route::get('cbd-products-export', [CbdController::class, 'exportProducts']);
Route::post('cbd-products-import', [CbdController::class, 'import'])->name('import');

Route::group(['namespace' => 'backend', 'as' => 'dashboard.', 'prefix' => 'dashboard', 'middleware' => ['auth']], function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // marketing-contacts Routes
    Route::group(['prefix' => 'marketing-contacts', 'as' => 'marketing-contact.'], function () {
        Route::get('/', [MarketingController::class, 'index'])->name('index');
        Route::get('/create', [MarketingController::class, 'create'])->name('create');
        Route::post('/store', [MarketingController::class, 'store'])->name('store');
        Route::post('/import-contacts', [MarketingController::class, 'importContacts'])->name('import.contacts');

        Route::group(['prefix' => '{contact}'], function () {
            Route::get('edit', [MarketingController::class, 'edit'])->name('edit');
            Route::post('update', [MarketingController::class, 'update'])->name('update');
            Route::get('delete', [MarketingController::class, 'delete'])->name('delete');
        });
    });

    // Campaign Routes
    Route::group(['prefix' => 'campaigns', 'as' => 'campaign.'], function () {
        Route::get('/', [CampaignController::class, 'index'])->name('index');
        Route::get('/create', [CampaignController::class, 'create'])->name('create');
        Route::post('/store', [CampaignController::class, 'store'])->name('store');
        Route::get('/sms-campaign', [CampaignController::class, 'smsCampaign'])->name('sms.campaign');
        Route::post('/send-sms', [CampaignController::class, 'sendSms'])->name('send.sms');

        Route::group(['prefix' => '{campaign}'], function () {
            Route::get('edit', [CampaignController::class, 'edit'])->name('edit');
            Route::post('update', [CampaignController::class, 'update'])->name('update');
            Route::get('delete', [CampaignController::class, 'delete'])->name('delete');
        });
    });





    // Banner Routes
    Route::group(['prefix' => 'banners', 'as' => 'banner.'], function () {
        Route::get('/', [bannerController::class, 'index'])->name('index');
        Route::get('/create', [bannerController::class, 'create'])->name('create');
        Route::post('/store', [bannerController::class, 'store'])->name('store');

        Route::group(['prefix' => '{banner}'], function () {
            Route::get('edit', [bannerController::class, 'edit'])->name('edit');
            Route::post('update', [bannerController::class, 'update'])->name('update');
            Route::get('delete', [bannerController::class, 'delete'])->name('delete');
        });
    });

    // Logo Routes
    Route::group(['prefix' => 'logos', 'as' => 'logo.'], function () {
        Route::get('/', [LogoController::class, 'index'])->name('index');
        Route::get('/create', [LogoController::class, 'create'])->name('create');
        Route::post('/store', [LogoController::class, 'store'])->name('store');

        Route::group(['prefix' => '{logo}'], function () {
            Route::get('edit', [LogoController::class, 'edit'])->name('edit');
            Route::post('update', [LogoController::class, 'update'])->name('update');
            Route::get('delete', [LogoController::class, 'delete'])->name('delete');
        });
    });
    // Profile Routes
    Route::group(['prefix' => 'profiles', 'as' => 'profile.'], function () {

        Route::get('/admin-profile', [HomeController::class, 'Profile'])->name('page');

        Route::group(['prefix' => '{profile}'], function () {

            Route::get('/edit-profile', [HomeController::class, 'EditProfile'])->name('edit');
            Route::post('/update-profile', [HomeController::class, 'UpdateProfile'])->name('update');
        });
    });


    Route::get('/change-password', [HomeController::class, 'UpdatePasswordPage'])->name('password.page');
    Route::post('/update-password', [HomeController::class, 'UpdatePassword'])->name('password.update');




    // permissions Routes
    Route::group(['prefix' => 'permissions', 'as' => 'permission.'], function () {
        Route::get('/', [PermissionsController::class, 'index'])->name('index');
        Route::get('/create', [PermissionsController::class, 'create'])->name('create');
        Route::post('/store', [PermissionsController::class, 'store'])->name('store');

        Route::group(['prefix' => '{permission}'], function () {
            Route::get('edit', [PermissionsController::class, 'edit'])->name('edit');
            Route::post('update', [PermissionsController::class, 'update'])->name('update');
            Route::get('delete', [PermissionsController::class, 'delete'])->name('delete');
        });
    });


    // roles Routes
    Route::group(['prefix' => 'roles', 'as' => 'role.'], function () {
        Route::get('/', [RolesController::class, 'index'])->name('index');
        Route::get('/create', [RolesController::class, 'create'])->name('create');
        Route::post('/store', [RolesController::class, 'store'])->name('store');

        Route::group(['prefix' => '{role}'], function () {
            Route::get('edit', [RolesController::class, 'edit'])->name('edit');
            Route::post('update', [RolesController::class, 'update'])->name('update');
            Route::get('delete', [RolesController::class, 'delete'])->name('delete');
        });
    });
    
      // Cart Orders (multi-item orders placed through the cart / checkout flow)
    Route::group(['prefix' => 'cart-orders', 'as' => 'cart-orders.'], function () {
        Route::get('/', [CartOrderController::class, 'index'])->name('index');
        Route::get('/{id}/view', [CartOrderController::class, 'show'])->name('view');
        Route::post('/{id}/status', [CartOrderController::class, 'updateStatus'])->name('status');
    });

    // users Routes
    Route::group(['prefix' => 'users', 'as' => 'user.'], function () {
        Route::get('/', [UsersController::class, 'index'])->name('index');
        Route::get('/create', [UsersController::class, 'create'])->name('create');
        Route::post('/store', [UsersController::class, 'store'])->name('store');

        Route::group(['prefix' => '{user}'], function () {
            Route::get('edit', [UsersController::class, 'edit'])->name('edit');
            Route::post('update', [UsersController::class, 'update'])->name('update');
            Route::get('delete', [UsersController::class, 'delete'])->name('delete');
        });
    });
    
       // Product Variation Routes
    Route::group(['prefix' => 'variations', 'as' => 'variation.'], function () {
        Route::get('/', [ProductVariationController::class, 'index'])->name('index');
        Route::get('create', [ProductVariationController::class, 'create'])->name('create');
        Route::post('store', [ProductVariationController::class, 'store'])->name('store');
        Route::get('product/{product:id}/edit', [ProductVariationController::class, 'edit'])->name('edit');
        Route::post('product/{product:id}/update', [ProductVariationController::class, 'update'])->name('update');
        Route::match(['get', 'post'], '{variation}/delete', [ProductVariationController::class, 'delete'])->name('delete');
    });


    // invoice Management Routes
    Route::group(['prefix' => 'invoices', 'as' => 'invoice.'], function () {
        Route::get('/', [InvoiceManagementController::class, 'index'])->name('index');
        Route::get('/create', [InvoiceManagementController::class, 'create'])->name('create');
        Route::post('/store', [InvoiceManagementController::class, 'store'])->name('store');
        Route::post('/store-invoice', [InvoiceManagementController::class, 'storeInvoice'])->name('store.invoice');
        Route::get('/blog-description', [InvoiceManagementController::class, 'BlogDescription'])->name('description');

        Route::group(['prefix' => '{invoice}'], function () {
            Route::get('view-customer-invoice', [InvoiceManagementController::class, 'ViewCustomerInvoice'])->name('show');
            Route::get('view-invoice', [InvoiceManagementController::class, 'ViewInvoice'])->name('view.invoice');
            Route::get('/create-invoice', [InvoiceManagementController::class, 'createInvoice'])->name('createInvoice');
            Route::get('/edit-invoice', [InvoiceManagementController::class, 'editInvoice'])->name('editInvoice');
            Route::post('/update-invoice', [InvoiceManagementController::class, 'UpdateInvoice'])->name('updateInvoice');
            Route::get('/send-invoice-mail-to-customer', [InvoiceManagementController::class, 'CustomerMail'])->name('customer.invoice.mail');
            Route::get('edit', [InvoiceManagementController::class, 'edit'])->name('edit');
            Route::post('update', [InvoiceManagementController::class, 'update'])->name('update');
            Route::get('delete', [InvoiceManagementController::class, 'delete'])->name('delete');
        });
    });

    // Price Match Routes
    Route::group(['prefix' => 'prices', 'as' => 'price.'], function () {
        Route::get('/', [PriceMatchController::class, 'index'])->name('index');

    });

    // Product Categories Management Routes
    Route::group(['prefix' => 'categories', 'as' => 'category.'], function () {
        Route::get('/', [ProductCategoryController::class, 'index'])->name('index');
        Route::get('/create', [ProductCategoryController::class, 'create'])->name('create');
        Route::post('/store', [ProductCategoryController::class, 'store'])->name('store');

        Route::group(['prefix' => '{category}'], function () {
            Route::get('edit', [ProductCategoryController::class, 'edit'])->name('edit');
            Route::post('update', [ProductCategoryController::class, 'update'])->name('update');
            Route::get('delete', [ProductCategoryController::class, 'delete'])->name('delete');
        });
    });

    // Blog Management Routes
    Route::group(['prefix' => 'blogs', 'as' => 'blog.'], function () {
        Route::get('/', [BlogController::class, 'index'])->name('index');
        Route::get('/create', [BlogController::class, 'create'])->name('create');
        Route::post('/store', [BlogController::class, 'store'])->name('store');
        Route::get('/blog-description', [BlogController::class, 'BlogDescription'])->name('description');

        Route::group(['prefix' => '{blog}'], function () {
            Route::get('edit', [BlogController::class, 'edit'])->name('edit');
            Route::post('update', [BlogController::class, 'update'])->name('update');
            Route::get('delete', [BlogController::class, 'delete'])->name('delete');
        });
    });




    // Product Management Routes
    Route::group(['prefix' => 'products', 'as' => 'product.'], function () {
        Route::get('/', [ProductManagementController::class, 'index'])->name('index');
        Route::get('/create', [ProductManagementController::class, 'create'])->name('create');
        Route::post('/store', [ProductManagementController::class, 'store'])->name('store');
        Route::get('/productdetail', [ProductManagementController::class, 'ProductDetail'])->name('detail');

        Route::group(['prefix' => '{product}'], function () {
            Route::get('edit', [ProductManagementController::class, 'edit'])->name('edit');
            Route::post('update', [ProductManagementController::class, 'update'])->name('update');
            Route::get('delete', [ProductManagementController::class, 'delete'])->name('delete');
        });
        Route::post('/{slug}/{id}', [ProductManagementController::class, 'deleteMedia'])->name('media.delete');
    });


    // cbd Product Routes
    Route::group(['prefix' => 'populars', 'as' => 'popular.'], function () {
        Route::get('/', [CbdController::class, 'index'])->name('index');
        Route::get('/create', [CbdController::class, 'create'])->name('create');
        Route::post('/store', [CbdController::class, 'store'])->name('store');
        Route::get('/productdetail', [CbdController::class, 'ProductDetail'])->name('detail');

        Route::group(['prefix' => '{product}'], function () {
            Route::get('edit', [CbdController::class, 'edit'])->name('edit');
            Route::post('update', [CbdController::class, 'update'])->name('update');
            Route::get('delete', [CbdController::class, 'delete'])->name('delete');
        });
    });

    // CBD Product Categories Management Routes
    Route::group(['prefix' => 'cbdcategories', 'as' => 'cbdcategory.'], function () {
        Route::get('/', [CbdCategoryController::class, 'index'])->name('index');
        Route::get('/create', [CbdCategoryController::class, 'create'])->name('create');
        Route::post('/store', [CbdCategoryController::class, 'store'])->name('store');

        Route::group(['prefix' => '{cbdcategory}'], function () {
            Route::get('edit', [CbdCategoryController::class, 'edit'])->name('edit');
            Route::post('update', [CbdCategoryController::class, 'update'])->name('update');
            Route::get('delete', [CbdCategoryController::class, 'delete'])->name('delete');
        });
    });

    //   NewsLetter
    Route::group(['prefix' => 'newsletters', 'as' => 'newsletter.'], function () {
        Route::get('/', [NewsLetterController::class, 'index'])->name('index');

    });



    //   Setting
    Route::group(['prefix' => 'contacts', 'as' => 'contact.'], function () {
        Route::get('/', [ContactController::class, 'index'])->name('index');
        Route::post('/store', [ContactController::class, 'store'])->name('store');

    });

    // Query
    Route::group(['prefix' => 'queries', 'as' => 'query.'], function () {
        Route::get('/', [QueryController::class, 'index'])->name('index');
        Route::group(['prefix' => '{order}'], function () {
            Route::get('view', [QueryController::class, 'viewOrder'])->name('view');
        });

    });


    Route::group(['prefix' => 'settings', 'as' => 'setting.'], function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::post('store', [SettingController::class, 'store'])->name('store');

        Route::group(['prefix' => '{setting}'], function () {
            Route::post('update', [SettingController::class, 'update'])->name('update');
        });
    });

 //   Orders (Original Orders)
    Route::group(['prefix' => 'old-orders', 'as' => 'order.'], function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/export-orders', [OrderController::class, 'export'])->name('export');
        Route::get('getorderdetail', [OrderController::class, 'OrderDetail'])->name('Orderdetail');
        Route::group(['prefix' => '{order}'], function () {
            Route::get('edit', [OrderController::class, 'OrderStatus'])->name('edit');
            Route::get('view', [OrderController::class, 'viewOrder'])->name('view');
            Route::post('update', [OrderController::class, 'OrderStatusSave'])->name('update');
            Route::get('delete', [OrderController::class, 'delete'])->name('delete');
        });
    });

    
    // Orders (Variation Quote Orders - New Module)
    Route::group(['prefix' => 'orders', 'as' => 'orders.'], function () {
        Route::get('/', [VariationQuoteController::class, 'index'])->name('index');
        Route::get('/{id}/view', [VariationQuoteController::class, 'show'])->name('view');
    });

    // Testimonial  Routes
    Route::group(['prefix' => 'testimonials', 'as' => 'testimonial.'], function () {
        Route::get('/', [TestimonialController::class, 'index'])->name('index');
        Route::get('/create', [TestimonialController::class, 'create'])->name('create');
        Route::post('/store', [TestimonialController::class, 'store'])->name('store');

        Route::group(['prefix' => '{testimonial}'], function () {
            Route::get('edit', [TestimonialController::class, 'edit'])->name('edit');
            Route::post('update', [TestimonialController::class, 'update'])->name('update');
            Route::get('delete', [TestimonialController::class, 'delete'])->name('delete');
        });
    });

    // Google Reviews Routes
    Route::group(['prefix' => 'google-reviews', 'as' => 'google-reviews.'], function () {
        Route::get('/', [GoogleReviewController::class, 'index'])->name('index');
        Route::post('/store', [GoogleReviewController::class, 'store'])->name('store');
        Route::post('/fetch', [GoogleReviewController::class, 'fetchReviews'])->name('fetch');
        Route::get('/manual-import', [GoogleReviewController::class, 'manualImport'])->name('manual-import');
        Route::post('/manual-import', [GoogleReviewController::class, 'processManualImport'])->name('process-manual-import');
        Route::get('/{id}', [GoogleReviewController::class, 'show'])->name('show');
        Route::delete('/{id}', [GoogleReviewController::class, 'destroy'])->name('destroy');
    });

    // Google Reviews Scraper Routes
    Route::group(['prefix' => 'google-reviews-scraper', 'as' => 'google-reviews-scraper.'], function () {
        Route::get('/', [GoogleReviewsScraperController::class, 'index'])->name('index');
        Route::post('/fetch', [GoogleReviewsScraperController::class, 'fetchReviews'])->name('fetch');
        Route::get('/get-reviews', [GoogleReviewsScraperController::class, 'getReviews'])->name('get-reviews');
    });

    // Google Maps Data API Routes
    Route::group(['prefix' => 'google-maps-data', 'as' => 'google-maps-data.'], function () {
        Route::get('/debugger', function() {
            return view('admin.google-maps-data.debugger');
        })->name('debugger');
        Route::get('/raw-data-viewer', function() {
            return view('admin.google-maps-data.raw-data-viewer');
        })->name('raw-data-viewer');
        Route::get('/raw-data', [GoogleMapsDataController::class, 'getRawData'])->name('raw-data');
    });

});

// Public routes for Google Maps data (no authentication required)
Route::get('/api/google-maps-data/raw-data', [GoogleMapsDataController::class, 'getRawData'])->name('api.google-maps-data.raw-data');
Route::get('/google-maps-data/raw-data-viewer', function() {
    return view('admin.google-maps-data.raw-data-viewer');
})->name('public.google-maps-data.raw-data-viewer');

// Public cron job route for Google Reviews
Route::get('/admin/google-reviews/cron-fetch', [GoogleReviewController::class, 'cronFetch'])->name('admin.google-reviews.cron-fetch');

// Review detail page route
Route::get('/review/{id}', [App\Http\Controllers\frontend\ReviewController::class, 'show'])->name('review.detail');

Route::middleware(['schemaOrg'])->group(function() {
    Route::get('/', [indexController::class, 'index'])->name('index');
    Route::post('order', [PagesController::class, 'StoreOrder'])->name('order.store');
    Route::get('thank-you', [PagesController::class, 'ThankYou'])->name('thankyou');

    Route::get('blog', [PagesController::class, 'BlogPage'])->name('blog.page');
    Route::get('blog/{slug}', [PagesController::class, 'BlogDetail'])->name('blog.detail');
    Route::get('about-us', [PagesController::class, 'AboutUs'])->name('aboutUs');
    Route::get('privacy-policy', [PagesController::class, 'SecurityAndPrivacy'])->name('SecurityAndPrivacy');
    Route::get('terms-of-services', [PagesController::class, 'TermsAndCondition'])->name('termsAndCondition');
    Route::get('refund-policy', [PagesController::class, 'refundPolicy'])->name('refundPolicy');
    Route::get('contact-us', [PagesController::class, 'ContactUs'])->name('contactUs');
    Route::get('frequently-asked-questions', [PagesController::class, 'FAQ'])->name('faq');
    Route::get('checkout', [PagesController::class, 'GetQuote'])->name('get.quote');
    Route::get('get-quotes', [PagesController::class, 'GetQuotepage'])->name('get.quotes');

    Route::post('save-contact', [PagesController::class, 'StoreContactUs'])->name('store.contact');
    Route::post('newsletter', [PagesController::class, 'StoreNewsLetter'])->name('newsletter');
    Route::get('all-products', [PagesController::class, 'AllProducts'])->name('allproducts');

    Route::get('price-match-challenge', [PagesController::class, 'priceMatch'])->name('price.match');
    Route::post('save-price-match-challenge', [PagesController::class, 'SavePriceMatchData'])->name('save.price.match');

    Route::get('cart', [PagesController::class, 'QuoteUseAsCart'])->name('cart.quote');;

    Route::get('search', [indexController::class, 'Search'])->name('search');
    Route::get('search-page', [indexController::class, 'SearchPage'])->name('searchpage');
    Route::post('submit-book-a-call', [indexController::class, 'BookCall'])->name('submit.book.call');

// popular product category
    Route::get('cbd-categories', [PagesController::class, 'PopularCategories'])->name('popularcategories');
    Route::get('cbd-categories/{slug}', [PagesController::class, 'PopularProductPage'])->name('popular');
    Route::get('activate-20-percent-offer', [PagesController::class, 'Offer']);
    
    Route::get('cart', [PagesController::class, 'CartPage'])->name('cart.quote');
    Route::get('checkout', [PagesController::class, 'CheckoutPage'])->name('checkout.page');
    Route::get('instant-quote', [PagesController::class, 'GetQuote'])->name('get.quote');


//  product category
    Route::get('box-by-industry', [PagesController::class, 'AllCategories'])->name('allcategories');
    Route::get('box-by-industry/{slug}', [PagesController::class, 'SubCategory'])->name('sub.category');
    Route::get('box-by-industry/{categorySlug}/{subcategorySlug}', [PagesController::class, 'ProductByCategory'])->name('product.by.category');
//Route::get('/{slug}',[PagesController::class, 'ProductDetail'])->name('product.detail');
    Route::get('quick-view', [PagesController::class, 'QuickView'])->name('quick.view');
    Route::get('generate-sitemap', [PagesController::class, 'sitemap'])->name('sitemap.xml');
    Route::get('storage-link', [PagesController::class, 'storageLink'])->name('storage.link');
    Route::get('optimize-clear', [PagesController::class, 'OptimizeClear'])->name('optimize.clear');


    Route::get('{slug}/{pagePrefix?}/{pageNumber?}', PageRenderer::class)
        ->name('page');

});


Route::post('/quill/image-upload', [QuillImageController::class, 'upload'])->name('quill.image.upload');
Route::post('/text-editor/image-upload', [EditorImageController::class, 'upload'])->name('editor.image.upload');



Route::get('/git-pull', function () {
    if (request()->query('token') !== env('GIT_PULL_TOKEN')) {
        abort(403, 'Unauthorized');
    }

    $output = [];
    $status = null;
    exec('git pull 2>&1', $output, $status);

    return response()->json([
        'status' => $status === 0 ? 'success' : 'error',
        'output' => $output
    ]);
})->name('git.pull');


Route::get('/storage-link', function () {
    try {
        Artisan::call('storage:link');
        return response()->json(['status' => 'success', 'message' => 'Storage link created successfully']);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to create storage link',
            'error' => $e->getMessage()
        ], 500);
    }
});

Route::get('/test', function () {
    dd('test');
});


Route::get('/storage/{id}/{conversion}/{filename}', function ($id, $conversion, $filename) {
    $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::findOrFail($id);
    
    $pathInfo = pathinfo($media->file_name);
    $conversionFileName = $pathInfo['filename'] . '-' . $conversion . '.' . $pathInfo['extension'];
    $path = storage_path('app/public/' . $media->id . '/conversions/' . $conversionFileName);
    
    if (!file_exists($path)) abort(404);
    
    return response()->file($path);
})->name('media.show');



