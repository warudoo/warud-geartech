<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        return view('orders.index', [
            'orders' => request()->user()->orders()->with('items', 'payment')->latest()->paginate(10),
        ]);
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        return view('orders.show', [
            'order' => $order->load('items.product', 'payment'),
        ]);
    }
}
