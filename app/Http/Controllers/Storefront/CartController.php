<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Http\Requests\Storefront\StoreCartItemRequest;
use App\Http\Requests\Storefront\UpdateCartItemRequest;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\CartService;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService,
    ) {
    }

    public function index()
    {
        $user = request()->user();

        return view('cart.index', [
            'cartItems' => $this->cartService->items($user),
            'subtotal' => $this->cartService->subtotal($user),
        ]);
    }

    public function store(StoreCartItemRequest $request)
    {
        $product = Product::query()->findOrFail($request->integer('product_id'));

        $this->cartService->add($request->user(), $product, $request->integer('quantity'));

        return redirect()->back()->with('status', 'Item added to cart.');
    }

    public function update(UpdateCartItemRequest $request, CartItem $cartItem)
    {
        abort_unless($cartItem->user_id === $request->user()->id, 403);

        $this->cartService->update($cartItem->load('product'), $request->integer('quantity'));

        return redirect()->route('cart.index')->with('status', 'Cart updated.');
    }

    public function destroy(CartItem $cartItem)
    {
        abort_unless($cartItem->user_id === request()->user()->id, 403);

        $this->cartService->remove($cartItem);

        return redirect()->route('cart.index')->with('status', 'Item removed.');
    }
}
