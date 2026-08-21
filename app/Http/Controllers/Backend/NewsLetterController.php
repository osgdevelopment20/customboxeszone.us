<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\NewsLetter;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class NewsLetterController extends Controller
{

    public function index(Request $request)
    {

        if ($request->ajax()) {


            $newsletters = NewsLetter::latest()->get();

            return DataTables::of($newsletters)
                ->addIndexColumn()

               


                ->escapeColumns([])
                ->make(true);
        }
        return view('backend.newsletter.index');
    }

  
}

