<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CbdCategory;
use App\Models\Product;
use App\Traits\APIResponse;
use App\Traits\GlobalResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HeaderMenuController extends Controller
{
    use APIResponse,GlobalResponseTrait;
    public function headerMenu()
    {

        $packageBoxesCategories =  Category::where('parent_id',"=",0)->active()->displayonmenu()->with('categories')->get()->map(function ($category){
                return[
                    'id' => $category->id,
                    'name' => $category->name,
                    'title' => $category->title,
                    'slug' => $category->slug,
                    'subcategory' =>    $category->categories->map(function ($subCategory){
                        return [
                            'id' => $subCategory->id,
                            'parent_id' => $subCategory->parent_id,
                            'name' => $subCategory->name,
                            'title' => $subCategory->title,
                            'slug' => $subCategory->slug,
                        ];
                    })
                ];
            });





        $categoriesBestSeller =  Category::active()
                ->where('display_in_best_seller', 1)
                ->with('procategories')
                ->get()->map(function ($category){
                    return[
                        'id' => $category->id,
                        'name' => $category->name,
                        'title' => $category->title,
                        'slug' => $category->slug,
                        'image' => asset('ProductImages/CategoryImages/'.$category->dropdown_category_image),
                        'category' => $category->procategories->slug
                    ];
                });


        $paperStock =  Category::active()
                ->paperstock()
                ->get()->map(function ($category){
                    return[
                        'id' => $category->id,
                        'name' => $category->name,
                        'title' => $category->title,
                        'slug' => $category->slug,
                        'image' => asset('ProductImages/CategoryImages/'.$category->category_img),
                    ];
                });


        $cbdMainCategory = Category::where('name','CBD')->first();
        $CBDCategories =  Category::where('parent_id',$cbdMainCategory->id)->get()->map(function ($cbdCategory) use ($cbdMainCategory){
                  return[
                        'id' => $cbdCategory->id,
                        'main_category'=> $cbdMainCategory->name,
                        'main_category_slug'=> $cbdMainCategory->slug,
                        'name' => $cbdCategory->name,
                        'title' => $cbdCategory->title,
                        'slug' => $cbdCategory->slug,
                    ];
                });



        $mylarBags =  Product::active()
                ->mylarbags()
                ->get()->map(function ($product){
                    return[
                        'id' => $product->id,
                        'product_title' => $product->product_title,
                        'product_name' => $product->product_name,
                        'slug' => $product->slug,
                        'product_front_image' => asset('ProductImages/ProductsFrontImages/'.$product->product_front_image),
                        'product_hover_image' => asset('ProductImages/ProductsBackImages/'.$product->product_back_image),
                    ];
                });


        $data= [
            'packageBoxesCategories' => $packageBoxesCategories,
            'categoriesBestSeller' =>   $categoriesBestSeller,
            'paperStock' =>  $paperStock,
            'CBDCategories' =>  $CBDCategories,
            'mylarBags' =>  $mylarBags,
        ];

        return $this->responseSuccess('Header Menu Data!',$data);

    }



}
