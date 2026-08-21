<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Logo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class LogoController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $logos = Logo::latest()->get();

            return DataTables::of($logos)

                ->addIndexColumn()
                ->editColumn('favicon_img', function ($logo) {

                    return '<span><img src="' . '/frontend/logos/'.$logo->favicon_img . '" class="img-thumbnail" alt="avatar"></span>';
                })

                ->editColumn('logo_img', function ($logo) {

                    return '<span><img src="' . '/frontend/logos/'.$logo->logo_img . '" class="img-thumbnail" alt="avatar"></span>';
                })

                ->addcolumn('action', function ($logo) {
                    return '<div >
          <a class="btn btn-primary"  href="' . route('admin.logo.edit', $logo) . '">Edit</a>
          <a class="btn btn-danger"  href="' . route('admin.logo.delete', $logo) . '">Delete</a>
                       </div>';

                })
                ->escapeColumns([])
                ->make(true);


        }

        return view('backend.logo.index');
    }

    public function create()
    {

        return view('backend.logo.create');
    }


    public function store(Request $request)
    {

        $logo = new Logo();

      if ($request->hasFile('logo_img'))
        {
            $image = $request->file('logo_img');
            $resizefile = Image::make($image)->resize(180, 60);
            $extension = $image->getClientOriginalExtension();
            $filename =  time() .'-' . rand(11111,99999) . '.' . $extension;
            $image_path = 'frontend/logos/' . $filename;
            $resizefile->save($image_path);
            $logo->logo_img =$filename;
        }

        if ($request->hasFile('favicon_img'))
        {
            $image = $request->file('favicon_img');
            $resizefile = Image::make($image)->resize(50, 50);
            $extension = $image->getClientOriginalExtension();
            $filename =  time() .'-' . rand(11111,99999) . '.' . $extension;
            $image_path = 'frontend/logos/' . $filename;
            $resizefile->save($image_path);
            $logo->favicon_img =$filename;
        }
        $logo->save();

        return redirect()->route('admin.logo.index')->with('success','Logo Store Successfully');

    }
    public function edit(Logo $logo)
    {

        return view('backend.logo.edit',compact('logo'));
    }

    public function update(Request $request , Logo $logo)
    {

       if ($request->hasFile('logo_img'))
        {
            $image = $request->file('logo_img');
            $resizefile = Image::make($image)->resize(180, 60);
            $extension = $image->getClientOriginalExtension();
            $filename =  time() .'-' . rand(11111,99999) . '.' . $extension;
            $image_path = 'frontend/logos/' . $filename;
            $resizefile->save($image_path);
            $logo->logo_img =$filename;
        }

        if ($request->hasFile('favicon_img'))
        {
            $image = $request->file('favicon_img');
            $resizefile = Image::make($image)->resize(50, 50);
            $extension = $image->getClientOriginalExtension();
            $filename =  time() .'-' . rand(11111,99999) . '.' . $extension;
            $image_path = 'frontend/logos/' . $filename;
            $resizefile->save($image_path);
            $logo->favicon_img =$filename;
        }
        $logo->save();
        return redirect()->route('admin.logo.index')->with('success','Logo Update Successfully');
    }

    public function delete(Logo $logo)
    {

        $logo->delete();

        return back()->with('success','Logo Deleted Successfully');


    }


}
