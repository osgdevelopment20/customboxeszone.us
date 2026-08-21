<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\CartOrderDataTable;
use App\Http\Controllers\Controller;
use App\Models\CartOrder;
use Illuminate\Http\Request;

class CartOrderController extends Controller
{
    /**
     * Display a listing of the cart orders.
     */
    public function index(CartOrderDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.cart-orders.index');
    }

    /**
     * Display the specified cart order.
     */
    public function show($id)
    {
        $order = CartOrder::with('items')->findOrFail($id);

        return view('backend.pages.cart-orders.detail', compact('order'));
    }

    /**
     * Move the order through its lifecycle.
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,contacted,completed,cancelled',
        ]);

        $order = CartOrder::findOrFail($id);
        $order->update(['status' => $validated['status']]);

        return redirect()
            ->route('dashboard.cart-orders.view', $order->id)
            ->with('success', 'Order status updated to ' . ucfirst($validated['status']) . '.');
    }
}
