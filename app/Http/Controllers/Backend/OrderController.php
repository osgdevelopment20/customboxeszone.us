<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\OrderDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\OrderStoreRequest;
use App\Models\Order;
use App\Exports\OrderExport;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class OrderController extends Controller
{
   public function index(OrderDataTable $dataTable)
   {
       return $dataTable->render('backend.pages.orders.index');
   }

   public function viewOrder(Order $order)
   {
       return view('backend.pages.orders.order-detail',compact('order'));
   }

    public function OrderStatus(Order $order)
       {

        return view('backend.order.edit',compact('order'));
       }

    public function OrderStatusSave(Request $request)

       {
           $order_id = $request->order_id;
           $orders = Order::find($order_id);

           if (isset($request->conform_order))
           {
               $orders->conform_order = $request->conform_order  = 1;
               $order_status  = 0;
               $orders->order_status = $order_status;
               $orders->save();

           }else
           {
               $orders->cancel_order = $request->cancel_order  = 1;
               $order_status  = 0;
               $orders->order_status = $order_status;
               $orders->save();
           }

           return redirect()->route('admin.order.index')->with('success','Order Status Set SuccessFully');

         }

          public function export()
              {
                 return (new OrderExport())->download('Orders.xlsx');
                   }




}
