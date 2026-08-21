<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        //dd($row);
        $product = new Product();
        $product->product_title       = $row['product_title'];
        $product->product_name        = $row['product_name'];
        $product->product_description = $row['product_description'] ?? '';
        $product->product_detail      = isset($row['product_detail']) ? $row['product_detail'] : '';
        $product->meta_keywords       = $row['meta_keywords'];
        $product->meta_description    = $row['meta_description'];
        $product->meta_title          = $row['meta_title'];
        isset($row['by_style']) ? $by_style = 1 : $by_style = 0;
        isset($row['mylar_bags']) ? $mylar_bags = 1 : $mylar_bags = 0;
        isset($row['status']) ? $status = 1 : $status = 0;
        isset($row['display_homepage']) ? $display_homepage = 1 : $display_homepage = 0;
        isset($row['sub_banner']) ? $sub_banner = 1 : $sub_banner = 0;
        isset($row['other_products']) ? $other_products = 1 : $other_products = 0;
        $product->related_products    = $row['related_products'] ?? [];
        $product->by_style  = $by_style;
        $product->mylar_bags  = $mylar_bags;
        $product->status = $status;
        $product->display_homepage  = $display_homepage;
        $product->sub_banner = $sub_banner;
        $product->other_products = $other_products;
        $product->product_front_image =$row['product_front_image'];
        $product->product_back_image =$row['product_back_image'];
        $product->product_multiple_images=$row['product_multiple_image'];
        $product->save();

        $category = Category::where('name','CBD')->first();
        if ($category)
        {
            $subcategory = Category::where('name',$row['main_category'])->first();
            if ($subcategory)
            {
                $product->category()->attach($subcategory->id);
            }else
            {
                $newsubCategory = new Category();
                $newsubCategory->name             = $row['main_category'];
                $newsubCategory->title            = $row['main_category'];
                $newsubCategory->parent_id        = $category->id;
                $newsubCategory->status           = 1;
                $newsubCategory->save();
                $product->category()->attach($newsubCategory->id);
            }

        }else
        {
            $newCategory = new Category();
            $newCategory->name             ='CBD';
            $newCategory->title            ='CBD';
            $newCategory->parent_id        = 0;
            $newCategory->status           = 1;
            $newCategory->save();
            if ($newCategory)
            {
                $subcategory = Category::where('name',$row['main_category'])->first();
                if ($subcategory)
                {
                    $product->category()->attach($subcategory->id);
                }else
                {
                    $newsubCategory = new Category();
                    $newsubCategory->name             = $row['main_category'];
                    $newsubCategory->title            = $row['main_category'];
                    $newsubCategory->parent_id        = $newCategory->id;
                    $newsubCategory->status           = 1;
                    $newsubCategory->save();
                    $product->category()->attach($newsubCategory->id);
                }

            }
        }


        // category add karna rahti hn abi


        return $product;
    }
}
