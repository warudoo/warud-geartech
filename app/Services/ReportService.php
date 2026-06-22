<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function summary(?Carbon $from = null, ?Carbon $to = null): array
    {
        $query = Order::query()
            ->whereIn('status', array_map(fn(OrderStatus $status) => $status->value, OrderStatus::paidStates()));

        if ($from) {
            $query->whereDate('paid_at', '>=', $from->toDateString());
        }

        if ($to) {
            $query->whereDate('paid_at', '<=', $to->toDateString());
        }

        $orders = $query->get();

        return [
            'gross_revenue' => (float) $orders->sum('total'),
            'paid_orders' => $orders->count(),
            'average_order_value' => $orders->count() > 0 ? (float) $orders->avg('total') : 0,
            'completed_orders' => $orders->where('status', OrderStatus::COMPLETED)->count(),
            'low_stock_products' => Product::query()->where('stock', '<=', 5)->count(),
            'recent_paid_orders' => $orders->sortByDesc('paid_at')->take(10),
            'daily_sales' => $this->dailySales($from, $to),
        ];
    }

    public function dailySales(?Carbon $from = null, ?Carbon $to = null): Collection
    {
        $query = Order::query()
            ->selectRaw('DATE(paid_at) as paid_date, COUNT(*) as orders_count, SUM(total) as revenue')
            ->whereIn('status', array_map(fn(OrderStatus $status) => $status->value, OrderStatus::paidStates()))
            ->whereNotNull('paid_at')
            ->groupBy('paid_date')
            ->orderByDesc('paid_date');

        if ($from) {
            $query->whereDate('paid_at', '>=', $from->toDateString());
        }

        if ($to) {
            $query->whereDate('paid_at', '<=', $to->toDateString());
        }

        return $query->get();
    }

    public function topCategories(?Carbon $from = null, ?Carbon $to = null)
{
    $query = DB::table('order_items')
        ->join('orders', 'order_items.order_id', '=', 'orders.id')
        ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
        ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
        ->whereIn(
            'orders.status',
            array_map(
                fn(OrderStatus $status) => $status->value,
                OrderStatus::paidStates()
            )
        )
        ->whereNotNull('orders.paid_at')
        ->selectRaw('
            COALESCE(categories.name, "Tanpa Kategori") as category_name,
            SUM(order_items.quantity) as total_sold
        ')
        ->groupBy('category_name')
        ->orderByDesc('total_sold');

    if ($from) {
        $query->whereDate('orders.paid_at', '>=', $from->toDateString());
    }

    if ($to) {
        $query->whereDate('orders.paid_at', '<=', $to->toDateString());
    }

    return $query->limit(10)->get();
    }
}
