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

        return redirect()->back()->with('toast', [
            'tone' => 'success',
            'title' => 'Produk berhasil ditambahkan ke cart',
            'message' => "{$product->name} sudah masuk ke cart Anda dan siap untuk checkout.",
            'action_label' => 'Lihat Cart',
            'action_url' => route('cart.index'),
            'timeout' => 4500,
        ]);
    }

    public function update(UpdateCartItemRequest $request, CartItem $cartItem)
    {
        abort_unless($cartItem->user_id === $request->user()->id, 403);

        $this->cartService->update($cartItem->load('product'), $request->integer('quantity'));

        return redirect()->route('cart.index')->with('toast', [
            'tone' => 'success',
            'title' => 'Keranjang berhasil diperbarui',
            'message' => 'Jumlah produk di cart sudah diperbarui.',
            'timeout' => 4000,
        ]);
    }

    public function destroy(CartItem $cartItem)
    {
        abort_unless($cartItem->user_id === request()->user()->id, 403);

        $this->cartService->remove($cartItem);

        return redirect()->route('cart.index')->with('toast', [
            'tone' => 'warning',
            'title' => 'Produk dihapus dari cart',
            'message' => 'Item tersebut sudah tidak ada di keranjang Anda.',
            'timeout' => 4000,
        ]);
    }
}
