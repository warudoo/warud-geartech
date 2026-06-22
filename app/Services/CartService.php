<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CartService
{
    public function getSelectedItems($user, array $ids)
    {
        return CartItem::whereIn('id', $ids)
            ->where('user_id', $user->id)
            ->with('product')
            ->get();
    }
    public function removeSelected($user, array $ids)
    {
        return CartItem::whereIn('id', $ids)
            ->where('user_id', $user->id)
            ->delete();
    }
    public function subtotalFromItems($items)
    {
        return $items->sum(
            fn($item) =>
            $item->quantity * $item->product->price
        );
    }
    public function items(User $user): Collection
    {
        return $user->cartItems()->with('product.category')->latest()->get();
    }

    public function count(User $user): int
    {
        return (int) $user->cartItems()->sum('quantity');
    }

    public function subtotal(User $user): float
    {
        return $this->items($user)->sum(fn(CartItem $item) => $item->lineTotal());
    }

    public function add(User $user, Product $product, int $quantity): CartItem
    {
        $this->ensureProductCanBeAdded($product, $quantity);

        return DB::transaction(function () use ($user, $product, $quantity) {
            $item = CartItem::query()->firstOrNew([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);

            $nextQuantity = ($item->exists ? $item->quantity : 0) + $quantity;

            $this->ensureQuantityWithinStock($product, $nextQuantity);

            $item->quantity = $nextQuantity;
            $item->save();

            return $item->load('product.category');
        });
    }

    public function update(CartItem $item, int $quantity): CartItem
    {
        $this->ensureProductCanBeAdded($item->product, $quantity);
        $this->ensureQuantityWithinStock($item->product, $quantity);

        $item->update(['quantity' => $quantity]);

        return $item->refresh()->load('product.category');
    }

    public function remove(CartItem $item): void
    {
        $item->delete();
    }

    public function clear(User $user): void
    {
        $user->cartItems()->delete();
    }

    protected function ensureProductCanBeAdded(Product $product, int $quantity): void
    {
        if (! $product->is_active) {
            throw ValidationException::withMessages([
                'product' => 'This product is not available for purchase.',
            ]);
        }

        if ($quantity < 1) {
            throw ValidationException::withMessages([
                'quantity' => 'Quantity must be at least 1.',
            ]);
        }
    }

    protected function ensureQuantityWithinStock(Product $product, int $quantity): void
    {
        if ($product->stock < $quantity) {
            throw ValidationException::withMessages([
                'quantity' => "Only {$product->stock} unit(s) are currently available.",
            ]);
        }
    }
}
