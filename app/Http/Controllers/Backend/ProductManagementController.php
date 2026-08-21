<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\ProductDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ProductStoreRequest;
use App\Http\Requests\Backend\ProductUpdateRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProductManagementController extends Controller
{

    public function index(ProductDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.products.index');

    }

    public function create()
    {
        $categories = Category::with('categories')->get();

        $products = Product::all();

        return view('backend.pages.products.create', compact('categories', 'products'));
    }

    public function store(ProductStoreRequest $request)
    {
       // dd($request->all());
        $product = new Product();

        $product->product_title       = $request->product_title;
        $product->product_name        = $request->product_name;
        $product->slug                = $request->slug;
        $product->product_description = $request->product_description ?? '';
        $product->product_detail      = $request->product_detail ?? '';
        $product->meta_keywords       = $request->meta_keywords;
        $product->meta_description    = $request->meta_description;
        $product->meta_title          = $request->meta_title;
        $product->by_style            = $request->by_style ? 1 : 0;
        $product->by_material         = $request->by_material ? 1 : 0;
        $product->by_industry         = $request->by_industry ? 1 : 0;
        $product->display_homepage    = $request->display_homepage ? 1 : 0;
        $product->status              = $request->status ?? 1;
        $product->faqs =              json_encode($request->faqs ?? []);
        // Note: client_story will be processed later in the method

        // Handle related products
        $related_products = $request->related_products ?? [];
        $product->related_products = json_encode($related_products);

        // Save product FIRST to get an ID before adding media
        $product->save();

        $productFrontImage = $request->file('product_front_image');
        $productBackImage =  $request->file('product_back_image');
        $productMultipleImage =  $request->file('product_multiple_images');

        if ($productFrontImage){
            $product->addMedia($productFrontImage)->toMediaCollection('product_front_image', 's3');
        }
        if ($productBackImage){
            $product->addMedia($productBackImage)->toMediaCollection('product_back_image', 's3');
        }

        if ($productMultipleImage)
        {
            foreach ($request->file('product_multiple_images') as $image)
            {
                $product->addMedia($image)->toMediaCollection('product_gallery', 's3');
            }
        }

        // Handle client story images
        if ($request->has('client_story')) {
            $clientStories = $request->client_story;
            $processedStories = [];

            foreach ($clientStories as $key => $story) {
                $processedStory = [
                    'description' => $story['description'] ?? '',
                    'media_id'    => null,
                ];

                // Store image in the media library if provided
                if (isset($story['image']) && $story['image']) {
                    $media = $product->addMedia($story['image'])->toMediaCollection('client_stories', 's3');
                    $processedStory['media_id'] = $media->id;
                }

                $processedStories[$key] = $processedStory;
            }

            $product->client_story = json_encode($processedStories);
            // Update client story after media processing
            $product->save();
        } else {
            // If no client_story data provided, set empty array
            $product->client_story = json_encode([]);
            $product->save();
        }

        $product->category()->attach($request->product_category_id);

        // Only clear cache if product is displayed on homepage or belongs to homepage categories
        if ($request->display_homepage) {
            \Cache::forget('homepage_products_with_category');
            clear_response_cache();
        } else {
            // Check if any attached categories are displayed on homepage
            $homepageCategories = Category::whereIn('id', $request->product_category_id)
                ->where('display_homepage', 1)
                ->exists();
            if ($homepageCategories) {
                \Cache::forget('homepage_products_with_category');
            clear_response_cache();
            }
        }

        return redirect()->route('dashboard.product.index')->with('success','Product added Successfully');

    }

    public function edit(Product $product)
    {

        $categories = Category::with('categories')->get();

        $products = Product::all();

        $images =  json_decode($product->product_multiple_images);

        $related_products= json_decode($product->related_products);


        return view('backend.pages.products.edit',compact('product','categories','images','related_products','products'));
    }

    public function update(ProductUpdateRequest $request, Product $product)
    {
        // Update product attributes
        $product->product_title       = $request->product_title;
        $product->product_name        = $request->product_name;
        $product->slug        = $request->slug;
        $product->product_description = $request->product_description ?? '';
        $product->product_detail      = $request->product_detail ?? '';
        $product->meta_keywords       = $request->meta_keywords;
        $product->meta_description    = $request->meta_description;
        $product->meta_title          = $request->meta_title;
        $product->by_style            = $request->by_style ? 1 : 0;
        $product->by_material         = $request->by_material ? 1 : 0;
        $product->by_industry         = $request->by_industry ? 1 : 0;
        $product->status              = $request->status ?? 1;
        $product->display_homepage    = $request->display_homepage ? 1 : 0;

        // Handle related products
        $related_products = $request->related_products ?? [];
        $product->related_products = json_encode($related_products);
        $product->faqs =              json_encode($request->faqs ?? []);
        // Note: client_story will be processed later in the method

        // Handle media uploads
        $productFrontImage = $request->file('product_front_image');
        $productBackImage = $request->file('product_back_image');
        $productMultipleImages = $request->file('product_multiple_images');

        // Update front image
        if ($productFrontImage) {
            // Clear existing front image
            $product->clearMediaCollection('product_front_image');
            // Add new front image
            $product->addMedia($productFrontImage)->toMediaCollection('product_front_image', 's3');
        }

        // Update back image
        if ($productBackImage) {
            // Clear existing back image
            $product->clearMediaCollection('product_back_image');
            // Add new back image
            $product->addMedia($productBackImage)->toMediaCollection('product_back_image', 's3');
        }

        // Update multiple images
        if ($productMultipleImages) {
            // Clear existing gallery images
            $product->clearMediaCollection('product_gallery');
            // Add new multiple images
            foreach ($productMultipleImages as $image) {
                $product->addMedia($image)->toMediaCollection('product_gallery', 's3');
            }
        }

        // Handle client story images
        $existingStories = json_decode($product->client_story, true) ?? [];
        $clientStories = $request->client_story ?? [];
        $processedStories = [];
        $keptMediaIds = [];

        foreach ($clientStories as $key => $story) {
            $processedStory = [
                'description' => $story['description'] ?? '',
                'media_id'    => null,
            ];

            if (isset($story['image']) && $story['image']) {
                // New image uploaded: store it in the media library
                $media = $product->addMedia($story['image'])->toMediaCollection('client_stories', 's3');
                $processedStory['media_id'] = $media->id;
            } else {
                // Keep the existing media reference if no new image uploaded
                $mediaId = $story['media_id'] ?? ($existingStories[$key]['media_id'] ?? null);
                $processedStory['media_id'] = $mediaId !== null && $mediaId !== '' ? (int) $mediaId : null;
            }

            if ($processedStory['media_id']) {
                $keptMediaIds[] = $processedStory['media_id'];
            }

            $processedStories[$key] = $processedStory;
        }

        // Delete media for removed stories or replaced images
        foreach ($product->getMedia('client_stories') as $media) {
            if (!in_array($media->id, $keptMediaIds, true)) {
                $media->delete();
            }
        }

        $product->client_story = json_encode($processedStories);

        // Check if homepage display is changing (before save)
        $wasOnHomepage = $product->getOriginal('display_homepage');

        // Save the product
        $product->save();

        // Sync categories
        $category = $request->input('product_category_id') ? $request->input('product_category_id') : [];
        $oldCategories = $product->category()->pluck('categories.id')->toArray();
        $product->category()->sync($category);

        // Only clear cache if:
        // 1. Product is/was displayed on homepage
        // 2. Homepage display flag changed
        // 3. Categories changed and any of them are displayed on homepage
        $shouldClearCache = false;

        if ($request->display_homepage || $wasOnHomepage) {
            $shouldClearCache = true;
        } elseif ($oldCategories != $category) {
            // Check if any old or new categories are on homepage
            $affectedCategories = array_unique(array_merge($oldCategories, $category));
            $homepageCategories = Category::whereIn('id', $affectedCategories)
                ->where('display_homepage', 1)
                ->exists();
            if ($homepageCategories) {
                $shouldClearCache = true;
            }
        }

        if ($shouldClearCache) {
            \Cache::forget('homepage_products_with_category');
            clear_response_cache();
        }

        return redirect()->route('dashboard.product.index')->with('success', 'Product Updated Successfully');
    }

    public function delete(Product $product)
    {
        // Check if product was displayed on homepage before deletion
        $wasOnHomepage = $product->display_homepage;

        // Check if product belongs to any homepage categories
        if (!$wasOnHomepage) {
            $homepageCategories = $product->category()
                ->where('display_homepage', 1)
                ->exists();
            $wasOnHomepage = $homepageCategories;
        }

        $product->delete();
        $product->category()->sync([]);
        $product->clearMediaCollection('product_back_image');
        $product->clearMediaCollection('product_front_image');
        $product->clearMediaCollection('product_gallery');
        $product->clearMediaCollection('client_stories');

        // Only clear cache if product was visible on homepage
        if ($wasOnHomepage) {
            \Cache::forget('homepage_products_with_category');
            clear_response_cache();
        }

        return back()->with('success', 'Product Deleted Successfully');
    }

    public function ProductDetail(Request $request)
    {

        $product_id = $request->id;
        $productDetail = Product::where('id', $product_id)->with('category')->first();
        $images = json_decode($productDetail->product_multiple_images);
        return view('backend.pages.products.productDetailModel', compact('productDetail', 'images'));

    }

    public function deleteMedia($slug, $mediaId)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $media = $product->getMedia('product_gallery')->find($mediaId);
        if ($media) {
            $media->delete();
        }
        return back()->with('success', 'Media deleted successfully.');
    }

}
