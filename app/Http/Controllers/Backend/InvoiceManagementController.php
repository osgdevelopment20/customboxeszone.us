<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Mail\CustomerInvoice;
use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\In;
use Yajra\DataTables\DataTables;


class InvoiceManagementController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {


            $customers = Customer::all();

            return DataTables::of($customers)
                ->addIndexColumn()

                ->addcolumn('invoice', function ($customers) {

                    return '<div >
                <a href="' . route('admin.invoice.createInvoice', $customers->id) . '"  class="bs-tooltip btn btn-success"  title="Create Invoice" >Create Invoice</a>
                       </div>';

                })
                ->addcolumn('action', function ($customers) {

                    return '<div >
                <a href="' . route('admin.invoice.show', $customers) . '"  class="bs-tooltip"  title="View Customer Invoice" ><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></a>
                <a href="' . route('admin.invoice.edit', $customers) . '" class="bs-tooltip"  title="Edit Customer" ><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>
                <a  href="' . route('admin.invoice.delete', $customers) . '" class="bs-tooltip"  title="Delete Customer" ><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>
                
 
                       </div>';

                })
                ->escapeColumns([])
                ->make(true);
        }


        return view('backend.customer-invoice.index');
    }

    public function create()
    {
        return view('backend.customer-invoice.create');
    }

    public function store(Request $request)
    {
        $customer = new Customer();
        $customer->name   = $request->name;
        $customer->email   = $request->email;
        $customer->phone   = $request->phone;
        $customer->address   = $request->address;
        $customer->shipping_address   = $request->shipping_address;
        $customer->save();

        return redirect()->route('admin.invoice.index')->with('success','Customer Data save SuccessFully');
    }

    public function edit(Customer $invoice)
    {
        return view('backend.customer-invoice.edit',compact('invoice'));
    }

    public function update(Request $request, Customer $invoice)
    {
        $invoice->name   = $request->name;
        $invoice->email   = $request->email;
        $invoice->phone   = $request->phone;
        $invoice->address   = $request->address;
        $invoice->shipping_address   = $request->shipping_address;
        $invoice->save();

        return redirect()->route('admin.invoice.index')->with('success','Customer Data Update SuccessFully');
    }


    public function delete(Customer $invoice)
    {

        $invoice->delete();

        return redirect()->back()->with('success','Customer Data Deleted SuccessFully');

    }

    public function createInvoice(Customer $invoice)
    {

        $customer_id = $invoice->id;

        return view('backend.customer-invoice.create-invoice',compact('customer_id'));
    }

    public function storeInvoice(Request $request)
    {

        $invoice = new Invoice();
        $invoice->customer_id = $request->customer_id;
        $invoice->box_name = $request->box_name;
        $invoice->box_size = $request->box_size;
        $invoice->unit_price = $request->unit_price;
        $invoice->total_amount = $request->total_amount;
        $invoice->quantity = $request->quantity;
        $invoice->discount = $request->discount;
        $invoice->save();
        return redirect()->route('admin.invoice.view.invoice',$invoice->id);

    }

    public function editInvoice(Invoice $invoice)
    {

        return view('backend.customer-invoice.edit-invoice',compact('invoice'));
    }

    public function UpdateInvoice(Request $request,Invoice $invoice)
    {

        $invoice->customer_id = $request->customer_id;
        $invoice->box_name = $request->box_name;
        $invoice->box_size = $request->box_size;
        $invoice->unit_price = $request->unit_price;
        $invoice->total_amount = $request->total_amount;
        $invoice->quantity = $request->quantity;
        $invoice->discount = $request->discount;
        $invoice->save();
        return redirect()->back()->with('success','Invoice Data Update SuccessFully');

    }




    public function ViewCustomerInvoice(Customer $invoice)
    {
            $customer_invoice = Invoice::where('customer_id',$invoice->id)->latest()->get();

        return view('backend.customer-invoice.invoice-detail',compact('customer_invoice'));

    }

    public function ViewInvoice(Invoice $invoice)
    {
        $customer_invoice_data = Invoice::where('id',$invoice->id)->with('customer')->first();



        return view('backend.customer-invoice.invoice',compact('customer_invoice_data'));
    }


    public function CustomerMail(Invoice $invoice)
    {
        $customer_invoice_mail = Invoice::where('id',$invoice->id)->with('customer')->first();

//        dd($customer_invoice_mail->customer->email);
        try {
            Mail::to($customer_invoice_mail->customer->email)->send(new CustomerInvoice($customer_invoice_mail));
        } catch (\Exception $exception) {
            Log::error('Invoice email failed: ' . $exception->getMessage());

            return redirect()->back()->with('error', 'The invoice email could not be sent. Check the mail settings and try again.');
        }

        return redirect()->back()->with('success','Mail Has Been send Successfully to the customer');



    }


}
