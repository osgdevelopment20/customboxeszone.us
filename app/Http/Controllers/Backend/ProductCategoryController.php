<?php

namespace App\Http\Controllers\Backend;
use App\DataTables\CategoryDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ProductCategoryRequest;
use App\Http\Requests\Backend\ProductCategoryUpdateRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\DataTables;

class ProductCategoryController extends Controller
{


    public function index(CategoryDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.categories.index');
    }


    public function create()
    {
        $categories = Category::where('parent_id','=','0')->get();
        return view('backend.pages.categories.create',compact('categories'));
    }


    public function store(ProductCategoryRequest $request)
    {
        $category = new Category();
        $category->name              = $request->name;
        $category->title             = $request->title;
        $category->slug              = $request->slug;
        $category->parent_id         = $request->parent_id;
        $category->description       = $request->description;
        $category->meta_description  = $request->meta_description;
        $category->meta_title        = $request->meta_title;
        $category->by_style          = $request->by_style ? 1 : 0;
        $category->by_material       = $request->by_material ? 1 : 0;
        $category->by_industry       = $request->by_industry ? 1 : 0;
        $category->display_homepage  = $request->display_homepage ? 1 : 0;
        $category->homepage_category_banner    = $request->homepage_category_banner ? 1 : 0;
        $category->status            = $request->status ?? 1;
        $category->faqs              = json_encode($request->faqs ?? []);

        $categoryImage =  $request->file('category_img');
        $homepageImage =  $request->file('homepage_img');
        if ($categoryImage){
            $category->addMedia($categoryImage)->toMediaCollection('category_img');
        }
        if ($homepageImage){
            $category->addMedia($homepageImage)->toMediaCollection('homepage');
        }

        $category->save();

        // Always clear menu cache as categories affect navigation
        \Cache::forget('menu_categories');
        clear_response_cache();
        \Cache::forget('homepage-categories');

        // Only clear homepage caches if category is displayed on homepage
        if ($request->homepage_category_banner) {
            \Cache::forget('homepage_subcategories');
        }

        if ($request->display_homepage) {
            \Cache::forget('homepage_products_with_category');
        }

        return redirect()->route('dashboard.category.index')->with('success','Product Category Added Successfully');
    }



    public function edit(Category $category)
    {
        $categories = Category::where('parent_id','=','0')->get();

        return view('backend.pages.categories.edit',compact('category','categories'));
    }


    public function update(ProductCategoryUpdateRequest $request, Category $category)
    {
        $category->name             =$request->name;
        $category->title            =$request->title;
        $category->parent_id        =$request->parent_id;
        $category->description      =$request->description;
        $category->detail           =$request->detail;
        $category->slug             =$request->slug;
        $category->meta_description =$request->meta_description;
        $category->meta_title       =$request->meta_title;
        $category->by_style            = $request->by_style ? 1 : 0;
        $category->by_material         = $request->by_material ? 1 : 0;
        $category->by_industry         = $request->by_industry ? 1 : 0;
        $category->display_homepage    = $request->display_homepage ? 1 : 0;
        $category->homepage_category_banner    = $request->homepage_category_banner ? 1 : 0;
        $category->status              = $request->status ?? 1;

        $category->faqs = json_encode($request->faqs ?? []);
        $categoryImage  =  $request->file('category_img');
        $homepageImage =  $request->file('homepage_img');

        if ($categoryImage){
            $category->clearMediaCollection('category_img');
            $category->addMedia($categoryImage)->toMediaCollection('category_img');
        }
        if ($homepageImage){
            $category->addMedia($homepageImage)->toMediaCollection('homepage');
        }

        // Check if homepage flags are changing
        $bannerChanged = $category->getOriginal('homepage_category_banner') != ($request->homepage_category_banner ? 1 : 0);
        $homepageChanged = $category->getOriginal('display_homepage') != ($request->display_homepage ? 1 : 0);
        $wasBannerCategory = $category->getOriginal('homepage_category_banner');
        $wasHomepageCategory = $category->getOriginal('display_homepage');

        $category->save();

        // Always clear menu cache as categories affect navigation
        \Cache::forget('menu_categories');
        clear_response_cache();
        \Cache::forget('homepage-categories');

        // Clear homepage_subcategories if:
        // 1. Banner flag is currently ON, or
        // 2. Banner flag was ON before (being removed), or
        // 3. Banner flag changed
        if ($request->homepage_category_banner || $wasBannerCategory || $bannerChanged) {
            \Cache::forget('homepage_subcategories');
        }

        // Clear homepage_products_with_category if:
        // 1. Display homepage flag is currently ON, or
        // 2. Display homepage flag was ON before (being removed), or
        // 3. Display homepage flag changed
        if ($request->display_homepage || $wasHomepageCategory || $homepageChanged) {
            \Cache::forget('homepage_products_with_category');
        }

        return redirect()->route('dashboard.category.index')->with('success','Product Category Updated Successfully');
    }

    public function delete(Category $category)
    {
        // Check if category was displayed on homepage before deletion
        $wasBannerCategory = $category->homepage_category_banner;
        $wasHomepageCategory = $category->display_homepage;

        try {
            $category->delete();
            $category->clearMediaCollection('category_img');
        } catch (\Exception $e) {
        }

        // Always clear menu cache as categories affect navigation
        \Cache::forget('menu_categories');
        clear_response_cache();
        \Cache::forget('homepage-categories');

        // Only clear homepage caches if category was visible on homepage
        if ($wasBannerCategory) {
            \Cache::forget('homepage_subcategories');
        }

        if ($wasHomepageCategory) {
            \Cache::forget('homepage_products_with_category');
        }

        return back()->with('success','Product Category Deleted Successfully');
    }



}
