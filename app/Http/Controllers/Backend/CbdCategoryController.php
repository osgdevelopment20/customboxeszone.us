<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ProductCategoryRequest;
use App\Http\Requests\Backend\ProductCategoryUpdateRequest;
use App\Models\CbdCategory;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class CbdCategoryController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $categories = CbdCategory::all();

            return DataTables::of($categories)
                ->addIndexColumn()
                ->addcolumn('status', function ($category) {
                    if ($category->status == '1')
                    {
                        return '<div >
                              <span class="badge badge-success"> Category Enable </span>
                             
                       </div>';

                    }else{
                        return '<div >
                              <span class="badge badge-danger"> Category Disable </span>
                             
                       </div>';
                    }


                })


                ->addcolumn('display_menu', function ($category) {
                    if ($category->display_menu == '1')
                    {
                        return '<div >
                              <span class="badge badge-success">Yes</span>
                             
                       </div>';

                    }else{
                        return '<div >
                              <span class="badge badge-danger"> Not</span>
                             
                       </div>';
                    }


                })


                ->addcolumn('action', function ($category) {
                    return '<div  >
          <a href="' . route('admin.cbdcategory.edit', $category) . '" class="bs-tooltip"  title="Edit Category"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 p-1 br-6 mb-1"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></a>
             <a  href="' . route('admin.cbdcategory.delete', $category) . '" class="bs-tooltip"  title="Delete Category"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash p-1 br-6 mb-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></a>
 
                            
                       </div>';

                })
                ->escapeColumns([])
                ->make(true);
        }
        return view('backend.cbdcategory.index');
    }


    public function create()
    {
        return view('backend.cbdcategory.create');
    }


    public function store(Request $request)
    {
        $category =new CbdCategory();
        $category->name             =$request->name;
        $category->title            =$request->title;
        $category->description      =$request->description;
        $category->detail           =$request->detail;
        $category->meta_description =$request->meta_description;
        $category->meta_title       =$request->meta_title;
        $category->custom_field     =$request->custom_field;
        $category->status           =isset($request->status) ? $request->status = 1 : $request->status = 0;
        $category->display_menu     =isset($request->display_menu) ? $request->display_menu = 1 : $request->display_menu = 0;
        if ($request->hasFile('category_img'))
        {
            $image = $request->file('category_img');
            $resizefile = Image::make($image)->resize(1100, 1100);
            $extension = $image->getClientOriginalExtension();
            $filename = time() .'-'. rand(11111,99999) . '.' . $extension;
            $image_path = 'ProductImages/CategoryImages/' . $filename;
            $resizefile->save($image_path);
            $category->category_img =$filename;
        }
        $category->save();

        return redirect()->route('admin.cbdcategory.index')->with('success','Product Category Created Successfully');
    }



    public function edit(CbdCategory $cbdcategory)
    {
        return view('backend.cbdcategory.edit',compact('cbdcategory'));
    }


    public function update(ProductCategoryUpdateRequest $request, CbdCategory $cbdcategory)
    {

        $cbdcategory->name             =$request->name;
        $cbdcategory->title            =$request->title;
        $cbdcategory->description      =$request->description;
        $cbdcategory->detail           =$request->detail;
        $cbdcategory->meta_description =$request->meta_description;
        $cbdcategory->meta_title       =$request->meta_title;
        $cbdcategory->custom_field     =$request->custom_field;
        $cbdcategory->status           =isset($request->status) ? $request->status = 1 : $request->status = 0;
        $cbdcategory->display_menu     =isset($request->display_menu) ? $request->display_menu = 1 : $request->display_menu = 0;
        if ($request->hasFile('category_img'))
        {
            $image = $request->file('category_img');
            $resizefile = Image::make($image)->resize(1100, 1100);
            $extension = $image->getClientOriginalExtension();
            $filename = time() .'-'. rand(11111,99999) . '.' . $extension;
            $image_path = 'ProductImages/CategoryImages/' . $filename;
            $resizefile->save($image_path);
            $cbdcategory->category_img =$filename;
        }
        $cbdcategory->save();
        return redirect()->route('admin.cbdcategory.index')->with('success','Product Category Updated Successfully');
    }

    public function delete(CbdCategory $cbdcategory)
    {
        try {
            $cbdcategory->delete();
        } catch (\Exception $e) {
        }

        return back()->with('success','Product Category Deleted Successfully');
    }
}
