@extends('layouts.app')

@section('content')
    <section class="mb-8">
        <p class="eyebrow">Cart Staging</p>
        <h1 class="page-title">Your Cart</h1>
    </section>

    <div class="grid gap-8 lg:grid-cols-[1.25fr_0.75fr]">
        <div class="space-y-4">
            @forelse($cartItems as $item)
                <article class="panel flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex gap-4">
                        <div class="h-24 w-24 overflow-hidden rounded-2xl border border-slate-200 bg-slate-100">
                            <img src="{{ $item->product->display_image_url }}" alt="{{ $item->product->name }}" class="h-full w-full object-cover">
                        </div>
                        <div>
                            <p class="eyebrow">{{ $item->product->category->name }}</p>
                            <h2 class="text-xl font-semibold text-slate-900">{{ $item->product->name }}</h2>
                            <p class="mt-2 text-sm text-slate-500">{{ $item->product->brand }} • {{ $item->product->sku }}</p>
                            <p class="mt-3 font-display text-2xl text-red-600">
                                Rp {{ number_format((float) $item->product->price, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
                        <form method="POST" action="{{ route('cart.update', $item) }}" class="flex items-end gap-3">
                            @csrf
                            @method('PATCH')
                            <div>
                                <label class="form-label">Qty</label>
                                <input type="number" name="quantity" min="1" max="{{ max($item->product->stock, 1) }}" value="{{ $item->quantity }}" class="form-input w-24">
                            </div>
                            <button type="submit" class="btn-secondary">Update</button>
                        </form>

                        <form method="POST" action="{{ route('cart.destroy', $item) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger">Remove</button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="panel text-slate-600">
                    Your cart is empty. Add products from the catalog before checking out.
                </div>
            @endforelse
        </div>

        <aside class="panel h-fit space-y-5">
            <div>
                <p class="eyebrow">Summary</p>
                <h2 class="section-title">Checkout Summary</h2>
            </div>
            <div class="panel-muted p-5">
                <div class="flex items-center justify-between text-sm text-slate-500">
                    <span>Items</span>
                    <span>{{ $cartItems->sum('quantity') }}</span>
                </div>
                <div class="mt-3 flex items-center justify-between text-lg font-semibold text-slate-900">
                    <span>Subtotal</span>
                    <span class="font-display text-2xl text-red-600">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
            </div>
            <p class="text-sm leading-6 text-slate-600">
                Stock is not reserved in the cart. Final stock validation happens during checkout, and stock is only deducted after a successful Midtrans callback confirms payment.
            </p>
            <div class="flex flex-col gap-3">
                <a href="{{ route('products.index') }}" class="btn-secondary justify-center">Continue Shopping</a>
                @if($cartItems->isNotEmpty())
                    <a href="{{ route('checkout.show') }}" class="btn-primary justify-center">Proceed To Checkout</a>
                @endif
            </div>
        </aside>
    </div>
@endsection
