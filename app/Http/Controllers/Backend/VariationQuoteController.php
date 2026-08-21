<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\VariationQuoteDataTable;
use App\Http\Controllers\Controller;
use App\Models\VariationQuote;
use Illuminate\Http\Request;

class VariationQuoteController extends Controller
{
    /**
     * Display a listing of the variation quotes.
     */
    public function index(VariationQuoteDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.orders.index');
    }

    /**
     * Display the specified variation quote.
     */
    public function show($id)
    {
        $quote = VariationQuote::with(['product', 'variation'])->findOrFail($id);
        return view('backend.pages.orders.variation-quote-detail', compact('quote'));
    }
}
