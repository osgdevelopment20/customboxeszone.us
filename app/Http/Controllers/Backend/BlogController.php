<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\BlogDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\SaveBlogRequest;
use App\Http\Requests\Backend\UpdateBlogRequest;
use App\Models\Blog;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class BlogController extends Controller
{

    public function index(BlogDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.blog.index');
    }

    public function create()
    {

        return view('backend.pages.blog.create');
    }

    public function store(SaveBlogRequest $request)
    {
        $blog = new Blog();
        $blog->title = $request->title;
        $blog->author_name = $request->author_name;
        $blog->blog_ldis = $request->blog_ldis;
        $blog->meta_keywords = $request->meta_keywords;
        $blog->blog_img_title = $request->blog_img_title;
        $blog->meta_description = $request->meta_description;
        $blog->meta_title = $request->meta_title;

        if ($request->status) {
            $status = 1;
        } else {
            $status = 0;
        }
        $blog->status = $status;
        if ($request->hasFile('blog_image')) {
            $blog->blog_image = $request->file('blog_image')->getClientOriginalName();
        }

        $blog->save();

        if ($request->hasFile('blog_image')) {
            $blog->addMediaFromRequest('blog_image')->toMediaCollection('blog_image', 's3');
        }

        // Clear homepage cache
        \Cache::forget('homepage_blogs');
        clear_response_cache();

        return redirect()->route('dashboard.blog.index')->with('success', 'Blog Data Has Been Saved SuccessFully');
    }

    public function edit(Blog $blog)
    {
        return view('backend.pages.blog.edit', compact('blog'));
    }

    public function update(UpdateBlogRequest $request, Blog $blog)
    {
        $blog->title = $request->title;
        $blog->author_name = $request->author_name;
        $blog->blog_ldis = $request->blog_ldis;
        $blog->meta_keywords = $request->meta_keywords;
        $blog->blog_img_title = $request->blog_img_title;
        $blog->meta_description = $request->meta_description;
        $blog->meta_title = $request->meta_title;

        if ($request->status) {
            $status = 1;
        } else {
            $status = 0;
        }
        $blog->status = $status;

        if ($request->hasFile('blog_image')) {
            $blog->blog_image = $request->file('blog_image')->getClientOriginalName();
            $blog->clearMediaCollection('blog_image');
            $blog->addMediaFromRequest('blog_image')->toMediaCollection('blog_image', 's3');
        }

        // if ($request->hasFile('blog_detail_image')) {
        //     $image = $request->file('blog_detail_image');
        //     $resizefile = Image::make($image)->resize(1370, 385);
        //     $extension = $image->getClientOriginalExtension();
        //     $filename = time() . '-' . rand(11111, 99999) . '.' . $extension;
        //     $image_path = 'Blog/blogdetailImages/' . $filename;
        //     $resizefile->save($image_path);
        //     $blog->blog_detail_image = $filename;
        // }

        $blog->save();

        // Clear homepage cache
        \Cache::forget('homepage_blogs');
        clear_response_cache();

        return redirect()->route('dashboard.blog.index')->with('success', 'Blog Data Has Been Updated SuccessFully');
    }

    public function delete(Blog $blog)
    {
        $blog->delete();

        // Clear homepage cache
        \Cache::forget('homepage_blogs');
        clear_response_cache();

        return redirect()->back()->with('success', 'Blog Data Has Been Deleted SuccessFully');
    }


    public function BlogDescription(Request $request)
    {
        $blog_id = $request->id;
        $blog = Blog::where('id', $blog_id)->first();

        return view('backend.pages.blog.blogModel', compact('blog'));

    }

}
