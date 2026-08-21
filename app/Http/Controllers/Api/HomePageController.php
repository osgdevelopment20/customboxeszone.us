<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Blog;
use App\Models\Category;
use App\Models\CbdProduct;
use App\Models\Product;
use App\Traits\APIResponse;
use App\Traits\GlobalResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class HomePageController extends Controller
{
    use APIResponse,GlobalResponseTrait;
public function index()
{

    try {
        $genralproducts = Product::active()->displayhomepage()->get();
        $products = $genralproducts->map(function ($product){
         return[
             'id' => $product->id,
             'product_title' => $product->product_title,
             'product_name' => $product->product_name,
             'slug' => $product->slug,
             'product_front_image' => asset('ProductImages/ProductsFrontImages/'.$product->product_front_image),
             'product_hover_image' => asset('ProductImages/ProductsBackImages/'.$product->product_back_image),
         ];
        });
        $banners = Banner::active()->latest()->get()->map(function ($banner)
        {
            return[
                'id' => $banner->id,
                'name' => $banner->name,
                'banner_image' => asset('frontend/banner/'.$banner->banner_img),
            ];
        });
        $blogs = Blog::latest()->limit(4)->get()->map(function ($blog)
        {
            return[
                'id' => $blog->id,
                'title' => $blog->title,
                'author_name' => $blog->author_name,
                'blog_image' => asset('Blog/blogThumbnail/'.$blog->blog_image),
                'slug' => $blog->slug,
            ];
        });

//        $productCategories = Category::active()->get();
//        $i =1;
//        foreach ($productCategories as $productCategory)
//        {
//            foreach ($productCategory->categories as $subCategories)
//            $subCategories = [
//                'id' => $subCategories->id,
//                'name' => $subCategories->name,
//                'slug' => $subCategories->slug,
//                'category_img' => asset('ProductImages/CategoryImages/'.$subCategories->category_img),
//                'count' => $i++,
//               'product_qty' => $subCategories->products->count(),
//            ] ;
//        }
        return ['products' => $products,'banners' => $banners , 'blogs' => $blogs];

    } catch (\Exception $e) {
        log::info($e->getMessage());

        return $this->returnResponseError(500, false, $e->getMessage());
    }

}


    public function Search(Request $request,$searchWord)
    {
       // dd($searchWord);
       // $search = $request->input('query');
        if (!empty($searchWord)){
            $products= Product::query()
                ->where("product_name", "LIKE", "%{$searchWord}%")
                ->orWhere("product_title", "LIKE", "%{$searchWord}%")
                ->get()
                ->map(function ($product){
                    return[
                        'id' => $product->id,
                        'product_name' => $product->product_name,
                        'product_title' => $product->product_title,
                        'slug' => $product->slug,
                        'product_front_image' => asset('ProductImages/ProductsFrontImages/'.$product->product_front_image),
                    ];
                });
            if ($products){
                return $this->responseSuccess('Searched Data!',$products);
            }else
            {
                return $this->responseSuccess('No Record!');
            }

        }else{
            return $this->responseSuccess('No Record!');
        }


    }



}
