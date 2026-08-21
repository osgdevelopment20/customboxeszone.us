<?php

namespace App\Http\Controllers\Backend;

use App\Exports\CBDProductExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\PopularProductRequest;
use App\Http\Requests\Backend\PopularUpdateProduct;
use App\Http\Requests\Backend\ProductStoreRequest;
use App\Http\Requests\Backend\ProductUpdateRequest;
use App\Imports\ProductsImport;
use Illuminate\Support\Facades\File;
use App\Models\CbdCategory;
use App\Models\CbdProduct;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class CbdController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {


            $products = CbdProduct::all();

            return DataTables::of($products)
                ->addIndexColumn()
                ->addcolumn('status', function ($product) {
                    if ($product->status == '1')
                    {
                        return '<div >
                              <span class="badge badge-success"> Enable </span>

                       </div>';

                    }else{
                        return '<div >
                              <span class="badge badge-danger"> Disable </span>

                       </div>';
                    }

                })


                ->addColumn('product_category', function ($product) {

                })



                ->editColumn('product_front_image', function ($product) {

                    return '<span><img src="' . '/ProductImages/ProductsFrontImages/'.$product->product_front_image . '" class="img-thumbnail w-75" alt="avatar" ></span>';
                })
                ->editColumn('product_back_image', function ($product) {

                    return '<span><img src="' . '/ProductImages/ProductsBackImages/'.$product->product_back_image . '" class="img-thumbnail w-75" alt="avatar"></span>';
                })

                ->addcolumn('action', function ($product) {
                    return '<div >
                <a href="javascript:void(0);"  onclick="productDetails('. $product->id .')" class="bs-tooltip"  title="Product Detail" ><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></a>
                <a href="' . route('admin.popular.edit', $product) . '" class="bs-tooltip"  title="Edit Product" ><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>
                <a  href="' . route('admin.popular.delete', $product) . '" class="bs-tooltip"  title="Delete Product" ><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>


                       </div>';

                })
                ->escapeColumns([])
                ->make(true);
        }


        return view('backend.cbdproduct.index');
    }

    public function create()
    {
        $categories = CbdCategory::all();
        $products = CbdProduct::all();

        return view('backend.cbdproduct.create',compact('categories','products'));
    }

    public function  store(PopularProductRequest $request)
    {
        $product = new CbdProduct();
        $product->product_title       = $request->product_title;
        $product->product_name        = $request->product_name;
        $product->category_id         = $request->category_id;
        $product->product_description = isset($request->product_description) ? $request->product_description : '';
        $product->product_detail      = isset($request->product_detail) ? $request->product_detail : '';
        $product->meta_keywords       = $request->meta_keywords;
        $product->meta_description    = $request->meta_description;
        $product->meta_title          = $request->meta_title;
        $product->status              = isset($request->status) ? $request->status = 1 : $request->status = 0;
        $product->display_homepage    = isset($request->display_homepage) ? $request->display_homepage = 1 : $request->display_homepage = 0;

        if ($request->related_products == null)
        {
            $related_products = [];

        }
        else{

            $related_products = $request->related_products;
        }
        $products_data = [];
        foreach ($related_products as $related_pro) {
            $products_data[] = $related_pro;
        }

        $product->related_products = json_encode($products_data);


        if ($request->hasFile('product_front_image'))
        {
            $image = $request->file('product_front_image');
            $resizefile = Image::make($image)->resize(1100, 1100);
            $extension = $image->getClientOriginalExtension();
            $filename = time() .'-'. rand(11111,99999) . '.' . $extension;
            $image_path = 'ProductImages/ProductsFrontImages/' . $filename;
            $resizefile->save($image_path);
            $product->product_front_image =$filename;
        }

        if ($request->hasFile('product_back_image'))
        {
            $image = $request->file('product_back_image');
            $resizefile = Image::make($image)->resize(1100, 1100);
            $extension = $image->getClientOriginalExtension();
            $filename = time() .'-'. rand(11111,99999) . '.' . $extension;
            $image_path = 'ProductImages/ProductsBackImages/' . $filename;
            $resizefile->save($image_path);
            $product->product_back_image =$filename;
        }

        if ($request->hasFile('product_multiple_images'))
        {
            foreach ($request->file('product_multiple_images') as $image)
            {

                $resizefile = Image::make($image)->resize(1100, 1100);
                $extension = $image->getClientOriginalExtension();
                $filename = time() .'-'. rand(11111,99999) . '.' . $extension;
                $image_path = 'ProductImages/ProductsMultipleImages/' . $filename;
                $resizefile->save($image_path);
                $data[] = $filename;

            }
            $product->product_multiple_images=json_encode($data);
        }

        $product->save();


        return redirect()->route('admin.popular.index')->with('success','Product added Successfully');




    }

    public function edit(CbdProduct $product)
    {

        $categories = CbdCategory::all();
        $products   = CbdProduct::all();

        $images =  json_decode($product->product_multiple_images);

        $related_products= json_decode($product->related_products);


        return view('backend.cbdproduct.edit',compact('product','categories','images','related_products','products'));
    }

    public function update(PopularUpdateProduct $request ,CbdProduct $product)
    {
        $product->product_title       = $request->product_title;
        $product->product_name        = $request->product_name;
        $product->slug                = $request->slug;
        $product->category_id         = $request->category_id;
        $product->product_description = isset($request->product_description) ? $request->product_description : '';
        $product->product_detail      = isset($request->product_detail) ? $request->product_detail : '';
        $product->meta_keywords       = $request->meta_keywords;
        $product->meta_description    = $request->meta_description;
        $product->meta_title          = $request->meta_title;
        $product->status              = isset($request->status) ? $request->status = 1 : $request->status = 0;
        $product->display_homepage    = isset($request->display_homepage) ? $request->display_homepage = 1 : $request->display_homepage = 0;

        if ($request->related_products == null)
        {
            $related_products = [];

        }
        else{

            $related_products = $request->related_products;
        }
        $products_data = [];
        foreach ($related_products as $related_pro) {
            $products_data[] = $related_pro;
        }

        $product->related_products = json_encode($products_data);


        if ($request->hasFile('product_front_image'))
        {
            $proImage = public_path("ProductImages/ProductsFrontImages/{$product->product_front_image}"); // get previous image from folder
                if (File::exists($proImage)) { // unlink or remove previous image from folder
                    unlink($proImage);
                }


            $image = $request->file('product_front_image');
            $resizefile = Image::make($image)->resize(1100, 1100);
            $extension = $image->getClientOriginalExtension();
            $filename = time() .'-'. rand(11111,99999) . '.' . $extension;
            $image_path = 'ProductImages/ProductsFrontImages/' . $filename;
            $resizefile->save($image_path);
            $product->product_front_image =$filename;
        }

        if ($request->hasFile('product_back_image'))
        {
              $proImage = public_path("ProductImages/ProductsBackImages/{$product->product_back_image}"); // get previous image from folder
            if (File::exists($proImage)) { // unlink or remove previous image from folder
                unlink($proImage);
            }

            $image = $request->file('product_back_image');
            $resizefile = Image::make($image)->resize(1100, 1100);
            $extension = $image->getClientOriginalExtension();
            $filename = time() .'-'. rand(11111,99999) . '.' . $extension;
            $image_path = 'ProductImages/ProductsBackImages/' . $filename;
            $resizefile->save($image_path);
            $product->product_back_image =$filename;
        }

        if ($request->hasFile('product_multiple_images'))
        {
            foreach ($request->file('product_multiple_images') as $image)
            {

                $resizefile = Image::make($image)->resize(1100, 1100);
                $extension = $image->getClientOriginalExtension();
                $filename = time() .'-'. rand(11111,99999) . '.' . $extension;
                $image_path = 'ProductImages/ProductsMultipleImages/' . $filename;
                $resizefile->save($image_path);
                $data[] = $filename;

            }
            $product->product_multiple_images=json_encode($data);
        }

        $product->save();
        return redirect()->route('admin.popular.index')->with('success','Product Updated  Successfully');

    }

    public function delete(CbdProduct $product)
    {
        $product->delete();

        return back()->with('success','Product Deleted Successfully');
    }

    public function ProductDetail(Request $request)
    {

        $product_id  = $request->id;
        $productDetail = CbdProduct::where('id',$product_id)->with('cbdcategory')->first();
        $images =  json_decode($productDetail->product_multiple_images);
        return view('backend.cbdproduct.productDetailModel',compact('productDetail','images'));

    }



    public function exportProducts()
    {
        return Excel::download(new CBDProductExport, 'cbd-products.xlsx');
    }

    public function import(Request  $request)
    {

        if ($request->hasFile('import'))
        {
            Excel::import(new ProductsImport, $request->import);
            return redirect()->back();
        }

    }
}
