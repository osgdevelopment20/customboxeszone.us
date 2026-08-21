<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PriceMatch;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PriceMatchController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {


            $priceMatch = PriceMatch::all();

            return DataTables::of($priceMatch)
                ->addIndexColumn()

                ->addcolumn('name', function ($priceMatch) {

                    return $priceMatch->name.' '.$priceMatch->l_name;

                })
                ->addcolumn('image', function ($priceMatch) {

                    return '<span><img src="' . '/frontend/pricematch/'.$priceMatch->image . '" class="img-thumbnail w-75" alt="avatar" ></span>';

                })

                ->escapeColumns([])
                ->make(true);
        }


        return view('backend.priceMatch.index');
    }

}
