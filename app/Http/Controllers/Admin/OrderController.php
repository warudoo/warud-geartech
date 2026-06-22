<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateOrderStatusRequest;
use App\Models\Order;
use App\Services\OrderService;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
    ) {
    }

    public function index()
    {
        return view('admin.orders.index', [
            'orders' => Order::query()
                ->with('user')
                ->when(request('status'), fn ($query, $status) => $query->where('status', $status))
                ->when(request('search'), function ($query, $search) {
                    $query->where(function ($nested) use ($search) {
                        $nested->where('order_number', 'like', "%{$search}%")
                            ->orWhere('customer_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
                })
                ->latest()
                ->paginate(12)
                ->withQueryString(),
        ]);
    }

    public function show(Order $order)
    {
        return view('admin.orders.show', [
            'order' => $order->load('items.product', 'user', 'payment'),
            'availableStatuses' => [
                OrderStatus::PROCESSING,
                OrderStatus::SHIPPED,
                OrderStatus::COMPLETED,
                OrderStatus::CANCELLED,
            ],
        ]);
    }

    public function update(UpdateOrderStatusRequest $request, Order $order)
    {
        $this->authorize('updateStatus', $order);

        $this->orderService->transitionToStatus(
            $order,
            OrderStatus::from($request->string('status')->value()),
            'admin',
        );

        return redirect()->route('admin.orders.show', $order)->with('status', 'Order status updated.');
    }
}
