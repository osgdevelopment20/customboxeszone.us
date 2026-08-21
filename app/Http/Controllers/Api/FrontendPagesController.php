<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\OrderStoreRequest;
use App\Http\Requests\GetQuoteRequest;
use App\Http\Resources\CategoryResource;
use App\Jobs\SendEmailJob;
use App\Jobs\SendQuoteEmailJob;
use App\Mail\OrderMail;
use App\Mail\QuoteMail;
use App\Models\Blog;
use App\Models\Category;
use App\Models\CbdCategory;
use App\Models\CbdProduct;
use App\Models\Order;
use App\Models\Product;
use App\Models\Quote;
use App\Traits\APIResponse;
use App\Traits\GlobalResponseTrait;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;

class FrontendPagesController extends Controller
{
    use APIResponse,GlobalResponseTrait;

    /*
      All Categories
      Sub Categories
      Categories with its Product
      Product Detail
    */



    public function allMainCategories()
    {
        $categories = Category::where('parent_id', '=', '0')->get();

        return CategoryResource::collection($categories);

    }

    public function allCategories()
    {
        $categories = Category::all();

        return CategoryResource::collection($categories);
    }

    public function allCategoriesForQuotePage()
    {
        $allCategories =  Category::where('parent_id',"!=",0)->active()->with('categories')->get()->map(function ($subCategory){
            return [
                'id' => $subCategory->id,
                'parent_id' => $subCategory->parent_id,
                'name' => $subCategory->name,
                'title' => $subCategory->title,
                'slug' => $subCategory->slug,
            ];
        });
        return $allCategories;
    }

    public function SubCategory($slug)
    {
        $proCategory = Category::where('slug', $slug)->first();
        $subCategory = $proCategory->categories()->get()->map(function ($subcategory){
            return [
                    'id'=> $subcategory->id,
                    'parent_id'=> $subcategory->parent_id,
                    'name'=> $subcategory->name,
                    'slug'=> $subcategory->slug,
                    'category_img' => asset('ProductImages/CategoryImages/'.$subcategory->category_img),
            ];
        });
        if ($subCategory)
        {
            $mainCategoryData = [
               'category' => [
                   'id' => $proCategory->id,
                   'name' => $proCategory->name,
                   'slug' => $proCategory->slug,
               ],
               'subCategory' => $subCategory
            ];
            return $this->responseSuccess('Category And Sub Category Data!',$mainCategoryData);
        }else
        {
            return $this->responseServerError('Data Not Found!');
        }

    }

    public function ProductByCategory($subcategorySlug )
    {
        $category = Category::where('slug', $subcategorySlug)->with('procategories')->first();
        $products = $category->products()->get()->map(function ($product){
            return[
                'id' => $product->id,
                'product_title' => $product->product_title,
                'product_name' => $product->product_name,
                'slug' => $product->slug,
                'product_front_image' => asset('ProductImages/ProductsFrontImages/'.$product->product_front_image),
                'product_hover_image' => asset('ProductImages/ProductsBackImages/'.$product->product_back_image),
            ];
        });
        if ($category && $products){
        $data= [
            'category' => [
                'id'=> $category->id,
                'name'=> $category->name,
                'slug'=> $category->slug,
                'main_category' => $category->procategories->name,
                'category_img' => asset('ProductImages/CategoryImages/'.$category->category_img),
            ],
            'products' =>  $products
        ];

            return $this->responseSuccess('Category With Its Product Data!',$data);
    }else
        {
            return $this->responseServerError('Data Not Found!');
        }


    }

    public function ProductDetail($slug)
    {

        $product = Product::where('slug', $slug)->where('status',1)->with('category')->first();
            $main_category = Category::where('id', $product->category[0]->parent_id)->first();
            $images = json_decode($product->product_multiple_images);
            $related_pro = json_decode($product->related_products);
            if (!empty($related_pro)) {
                $related_products = Product::whereIn('id', $related_pro)->get()->map(function ($product){
                    return[
                        'id' => $product->id,
                        'product_title' => $product->product_title,
                        'product_name' => $product->product_name,
                        'slug' => $product->slug,
                        'product_front_image' => asset('ProductImages/ProductsFrontImages/'.$product->product_front_image),
                        'product_hover_image' => asset('ProductImages/ProductsBackImages/'.$product->product_back_image),
                    ];
                });
            } else {
                $related_products = '';
            }

            $proImage= [];
            foreach ($images as $image)
            {
                $proImage[] = asset('ProductImages/ProductsMultipleImages/'.$image);
            }

            $data= [
                'product' => $product,
                'productImages' =>   $proImage,
                'relatedProduct' =>  $related_products
            ];
            return $this->responseSuccess('Category With Its Product Data!',$data);

    }

    /*
      CBD Categories
      Products with CBD Category
    */

    public function CbdCategories()
    {
        $cbdMainCategory = Category::where('name','CBD')->first();
        if ($cbdMainCategory)
        {
            $cbdCategories = Category::where('parent_id',$cbdMainCategory->id)->get()->map(function ($cbdCategory) use ($cbdMainCategory){
                return[
                    'id'=> $cbdCategory->id,
                    'main_category'=> $cbdMainCategory->name,
                    'main_category_slug'=> $cbdMainCategory->slug,
                    'title'=> $cbdCategory->title,
                    'name'=> $cbdCategory->name,
                    'slug'=> $cbdCategory->slug,
                    'meta_description'=> $cbdCategory->meta_description,
                    'meta_title'=> $cbdCategory->meta_title,
                    'category_img' => asset('ProductImages/CategoryImages/'.$cbdCategory->category_img),
                ];
            });
            $data = [
                'CBDCategories' => $cbdCategories
            ];
            return $this->responseSuccess('CBD Categories Data!',$data);
        }
        return $this->responseSuccess('CBD Categories Not Found!');

    }
    public function CbdCategoryWithProducts($slug)
    {

        $cbdCategory = CbdCategory::where('slug', $slug)->first();
        $products = CbdProduct::Where('category_id', $cbdCategory->id)->get()->map(function ($product){
            return[
                'id' => $product->id,
                'product_title' => $product->product_title,
                'product_name' => $product->product_name,
                'slug' => $product->slug,
                'product_front_image' => asset('ProductImages/ProductsFrontImages/'.$product->product_front_image),
                'product_hover_image' => asset('ProductImages/ProductsBackImages/'.$product->product_back_image),
            ];
        });

        $data = [
            'CBDCategory' => [
                'name' => $cbdCategory->name,
                'title' => $cbdCategory->title,
            ],
            'CBDProducts' => $products
        ];

        return $this->responseSuccess('CBD Category With Its Products Data!',$data);
    }


    /*
      All Products
    */
    public function allProducts()
    {
        $allProducts = Product::where('status', 1)->paginate(20);
        $products = $allProducts->map(function ($product){
            return[
                'id' => $product->id,
                'product_title' => $product->product_title,
                'product_name' => $product->product_name,
                'slug' => $product->slug,
                'product_front_image' => asset('ProductImages/ProductsFrontImages/'.$product->product_front_image),
                'product_hover_image' => asset('ProductImages/ProductsBackImages/'.$product->product_back_image),
            ];
        });
      //  dd($genralProducts,$cbdProducts,$products);
        $data = [
            'allProducts' => $products,
            'totalProducts' => $allProducts->total(),
        ];
        return $this->responseSuccess('All Products Data!',$data);
    }


    public function allProductsForDetailPage()
    {
        $allProducts = Product::where('status', 1)->get();
        $products = $allProducts->map(function ($product){
            return[
                'id' => $product->id,
                'product_title' => $product->product_title,
                'product_name' => $product->product_name,
                'slug' => $product->slug,
                'product_front_image' => asset('ProductImages/ProductsFrontImages/'.$product->product_front_image),
                'product_hover_image' => asset('ProductImages/ProductsBackImages/'.$product->product_back_image),
            ];
        });
        $data = [
            'allProducts' => $products,
        ];
        return $this->responseSuccess('All Products Data!',$data);
    }


    /*
       Blog
        Blog Detail
     */

    public function BlogPage()
    {
        $blogs = Blog::latest()->paginate(15)->map(function ($blog){
            return[
                'id' => $blog->id,
                'title' => $blog->title,
                'blog_image' => asset('Blog/blogImages/'.$blog->blog_image),
                'slug' => $blog->slug,
                'publishedDate' => date_format($blog->created_at," jS F Y"),
            ];
        });

        return $this->responseSuccess('Blogs get successfully!!',$blogs);
    }
    public function AllBlogForDetailPage()
    {

        $blogs = Blog::latest()->get()->map(function ($blog){
            return[
                'id' => $blog->id,
                'title' => $blog->title,
                'blog_image' => asset('Blog/blogImages/'.$blog->blog_image),
                'slug' => $blog->slug,
                'publishedDate' => date_format($blog->created_at," jS F Y"),
            ];
        });

        return $this->responseSuccess('Blogs get successfully!!',$blogs);
    }



    public function BlogDetail($slug)
    {
        $blog = Blog::where('slug', $slug)->first();
        $recent_blog = Blog::latest()->take(10)->get()->map(function ($blog){
            return[
                'id' => $blog->id,
                'title' => $blog->title,
                'blog_image' => asset('Blog/blogThumbnail/'.$blog->blog_image),
                'slug' => $blog->slug,
                'publishedDate' => date_format($blog->created_at," jS F Y"),
            ];
        });

        $data = [
            'blog' => [
                'id' => $blog->id,
                'title' => $blog->title,
                'author_name' => $blog->author_name,
                'slug' => $blog->slug,
                'blog_image' => asset('Blog/blogImages/'.$blog->blog_image),
                'meta_title' => $blog->meta_title,
                'meta_keywords' => $blog->meta_keywords,
                'meta_description' => $blog->meta_description,
                'description' => $blog->description,
                'publishedDate' => date_format($blog->created_at," jS F Y"),
            ],
            'recentBlog' => $recent_blog,
        ];
        return $this->responseSuccess('Blog get successfully!!',$data);

    }


    // Get Quote

    public function getQuote(Request $request)
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
        $order->product_name = isset($request->category) ? $request->category : '';
        $order->product_title = isset($request->category) ? $request->category : '';
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
        $order->save();
        if ($order)
        {
            try {

                Mail::to('quote@cusotmboxeszone.com')->send(new OrderMail($order));

            } catch (\Exception $exception) {

                Log::error($exception->getMessage());

            }
            return $this->responseSuccess('Quote Send Successfully',['quote_id' => $order->id]);
        }else
        {
            return $this->returnResponseError(500, false, 'Quote did not Send Successfully' );
        }

        /*$token = $request->input('g-recaptcha-response');

        if ($token) {
            $client = new Client();
            $response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
                'form_params' => array(
                    'secret'    => sys_settings('recaptcha_secret_key'),
                    'response'  => $token
                )
            ]);
            $results = json_decode($response->getBody()->getContents());
        }
        else
        {
            return $this->returnResponseError(500, false, 'Please Check ReCaptcha' );
        }*/

    }


    public function StoreContactUs(GetQuoteRequest $request)
    {

        $quote = new Quote();
        $quote->name = $request->name;
        $quote->phone = $request->phone;
        $quote->email = $request->email;
        $quote->specification = $request->specification;
        $quote->save();
        try {

            Mail::to('quote@cusotmboxeszone.com')->send(new QuoteMail($quote));

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
        return $this->responseSuccess('Quote Send Successfully',$quote);

    }




}
