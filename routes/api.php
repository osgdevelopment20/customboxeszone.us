<?php

use App\Http\Controllers\Api\FrontendPagesController;
use App\Http\Controllers\Api\HeaderMenuController;
use App\Http\Controllers\Api\HomePageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\DeployController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Trigger a git pull of the latest code. Protected by the DEPLOY_SECRET.
Route::post('/deploy/pull', [DeployController::class, 'pull'])->name('deploy.pull');
// Report what the deploy would do, without changing anything. Same secret.
Route::post('/deploy/status', [DeployController::class, 'status'])->name('deploy.status');


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



Route::group(['prefix' => 'v1'], function () {

    Route::get('/homepage',[HomePageController::class ,'index']);
    Route::get('/header-menu',[HeaderMenuController::class ,'headerMenu']);

    //  Product Category
    Route::get('box-by-industry', [FrontendPagesController::class, 'allMainCategories']);
    Route::get('all-categories', [FrontendPagesController::class, 'allCategories']);
    Route::get('box-by-industry/{slug}',[FrontendPagesController::class, 'SubCategory']);
    Route::get('box-by-industry/product-by-category/{subcategorySlug}',[FrontendPagesController::class, 'ProductByCategory']);
    Route::get('product/{slug}',[FrontendPagesController::class, 'ProductDetail']);
    Route::get('quick-view',[FrontendPagesController::class, 'QuickView']);


    // CBD product category
    Route::get('cbd-categories', [FrontendPagesController::class, 'CbdCategories']);
    Route::get('cbd-categories/{slug}', [FrontendPagesController::class, 'CbdCategoryWithProducts']);

    // All product
    Route::get('all-products', [FrontendPagesController::class, 'allProducts']);
    Route::get('all-products-for-detail-page', [FrontendPagesController::class, 'allProductsForDetailPage']);

    // All categories
    Route::get('all-categories-for-quote-page', [FrontendPagesController::class, 'allCategoriesForQuotePage']);



    // Blogs
    Route::get('blogs', [FrontendPagesController::class, 'BlogPage']);
    Route::get('all-blogs', [FrontendPagesController::class, 'AllBlogForDetailPage']);
    Route::get('blog/{slug}', [FrontendPagesController::class, 'BlogDetail']);

    // Get Quote

    Route::post('get-quote', [FrontendPagesController::class, 'getQuote']);
    Route::post('contact-us', [FrontendPagesController::class, 'StoreContactUs']);

    // for search
    Route::get('search/{searchWord}', [HomePageController::class, 'Search']);
});

