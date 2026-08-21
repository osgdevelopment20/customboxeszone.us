<?php

namespace App\Http\Controllers\Backend;


use App\DataTables\BannerDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\SaveBannerRequest;
use App\Http\Requests\Backend\SaveBlogRequest;
use App\Http\Requests\Backend\UpdateBannerRequest;
use App\Http\Requests\Backend\UpdateBlogRequest;
use App\Models\Banner;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\DataTables;

class bannerController extends Controller
{
    public function index(BannerDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.banner.index');
    }


    public function create()
    {

        return view('backend.pages.banner.create');
    }

    public function store(SaveBannerRequest $request)
    {

        $banner = new Banner();
        $banner->name = isset($request->name) ? $request->name : "";
        $banner->banner_url = isset($request->banner_url) ? $request->banner_url : "";
        $banner->status = $request->status ? 1 : 0;

        if ($request->hasFile('banner_img')) {
            $image = $request->file('banner_img');

            $extension = $image->getClientOriginalExtension();

            $filename = rand(111, 99999) . '.' . $extension;

            $image_path = 'frontend/banner/' . $filename;

            Image::make($image)->resize(965, 450)->save($image_path);
            $banner->banner_img = $filename;
        }


        $banner->save();

        return redirect()->route('dashboard.banner.index')->with('success', 'Banner Store Successfully');

    }
    public function edit(Banner $banner)
    {

        return view('backend.pages.banner.edit', compact('banner'));
    }
    public function update(UpdateBannerRequest $request, Banner $banner)
    {

        $banner->name = isset($request->name) ? $request->name : "";
        $banner->banner_url = isset($request->banner_url) ? $request->banner_url : "";
        $banner->status = $request->status ? 1 : 0;

        if ($request->hasFile('banner_img')) {
            $image = $request->file('banner_img');
            $extension = $image->getClientOriginalExtension();
            $filename = rand(111, 99999) . '.' . $extension;
            $image_path = 'frontend/banner/' . $filename;
            Image::make($image)->resize(965, 450)->save($image_path);
            $banner->banner_img = $filename;
        }


        $banner->save();
        return redirect()->route('dashboard.banner.index')->with('success', 'Banner Update Successfully');
    }

    public function delete(Banner $banner)
    {

        $banner->delete();

        $img_path = 'frontend/banner/' . $banner->banner_img;

        if (File::exists($img_path)) {
            File::delete($img_path);
        }

        return back()->with('success', 'Banner Deleted Successfully');


    }




}
