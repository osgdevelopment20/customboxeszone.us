<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\DataTables;

class TestimonialController extends Controller
{


    public function index(Request $request)
    {
        if ($request->ajax()) {


            $testimonials = Testimonial::all();

            return DataTables::of($testimonials)
                ->addIndexColumn()
                ->addcolumn('status', function ($testimonial) {
                    if ($testimonial->status == '1')
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

                ->editColumn('image', function ($testimonial) {

                    return '<span><img src="' . '/Testimonial/'.$testimonial->image . '" class="img-thumbnail w-75" alt="avatar" ></span>';
                })

                ->addcolumn('action', function ($testimonial) {
                    return '<div >

                <a href="' . route('admin.testimonial.edit', $testimonial) . '" class="bs-tooltip"  title="Edit Testimonial" ><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>
                <a  href="' . route('admin.testimonial.delete', $testimonial) . '" class="bs-tooltip"  title="Delete Testimonial" ><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>
                       </div>';

                })
                ->escapeColumns([])
                ->make(true);
        }


        return view('backend.testimonial.index');
    }

    public function create()
    {
       return view('backend.testimonial.create');
    }
    public function store(Request $request)
    {

        $testimonial = new Testimonial();
        $testimonial->name = $request->name;
        $testimonial->description = $request->description;
        if ($request->status)
        {
            $status =1;
        }
        $testimonial->status = $status;

        if ($request->hasFile('image'))
        {
            $image = $request->file('image');
            $resizefile = Image::make($image)->resize(200, 200);
            $extension = $image->getClientOriginalExtension();
            $filename = time() .'-'. rand(11111,99999) . '.' . $extension;
            $image_path = 'Testimonial/' . $filename;
            $resizefile->save($image_path);
            $testimonial->image =$filename;
        }
        $testimonial->save();

        return redirect()->route('admin.testimonial.index')->with('success','Testimonial Stored SuccessFully');

    }

    public function edit(Testimonial $testimonial)
    {

        return view('backend.testimonial.edit',compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $testimonial->name = $request->name;
        $testimonial->description = $request->description;
        if ($request->status)
        {
            $status =1;
        }
        $testimonial->status = $status;

        if ($request->hasFile('image'))
        {
            $image = $request->file('image');
            $resizefile = Image::make($image)->resize(200, 200);
            $extension = $image->getClientOriginalExtension();
            $filename = time() .'-'. rand(11111,99999) . '.' . $extension;
            $image_path = 'Testimonial/' . $filename;
            $resizefile->save($image_path);
            $testimonial->image =$filename;
        }
        $testimonial->save();

        return redirect()->route('admin.testimonial.index')->with('success','Testimonial Updated SuccessFully');

    }

    public function delete(Testimonial $testimonial)
    {

        $testimonial->delete();
        return redirect()->back()->with('success','Testimonial Deleted Successfully');

    }
}
