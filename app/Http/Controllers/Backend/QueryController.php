<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\QuoteDataTable;
use App\Http\Controllers\Controller;
use App\Models\Quote;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class QueryController extends Controller
{


    public function index(QuoteDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.queries.index');
    }

    public function viewOrder(Quote $order)
    {
        return view('backend.pages.queries.query-detail',compact('order'));
    }

}
