<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class StockService
{
    public function deductForPaidOrder(Order $order): Order
    {
        return DB::transaction(function () use ($order) {
            $lockedOrder = Order::query()
                ->with('items')
                ->lockForUpdate()
                ->findOrFail($order->id);

            if ($lockedOrder->stock_deducted_at) {
                return $lockedOrder;
            }

            foreach ($lockedOrder->items as $item) {
                /** @var Product $product */
                $product = Product::query()->lockForUpdate()->findOrFail($item->product_id);

                if ($product->stock < $item->quantity) {
                    throw new RuntimeException("Insufficient stock for {$product->name}.");
                }

                $product->decrement('stock', $item->quantity);
            }

            $lockedOrder->forceFill([
                'stock_deducted_at' => now(),
            ])->save();

            return $lockedOrder->refresh();
        });
    }
}
