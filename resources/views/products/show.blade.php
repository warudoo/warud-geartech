@extends('layouts.app')

@section('content')
    <section class="grid gap-8 lg:grid-cols-[1.05fr_0.95fr]">
        <div class="panel overflow-hidden">
            <div class="aspect-[4/3] overflow-hidden rounded-3xl border border-slate-200 bg-slate-100">
                @if($product->featured_image || $product->image_url)
                    <img src="{{ $product->featured_image ?: $product->image_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                @else
                    <div class="h-full w-full bg-[radial-gradient(circle_at_top,_rgba(220,38,38,0.18),_transparent_55%),linear-gradient(180deg,_rgba(255,255,255,0.9),_rgba(241,245,249,1))]"></div>
                @endif
            </div>
        </div>

        <div class="panel space-y-6">
            <div>
                <p class="eyebrow">{{ $product->category->name }}</p>
                <h1 class="mt-2 page-title">{{ $product->name }}</h1>
                <p class="mt-2 text-sm uppercase tracking-[0.18em] text-slate-500">{{ $product->brand }} • {{ $product->sku }}</p>
            </div>

            <p class="font-display text-4xl text-red-600">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</p>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="panel-muted p-4">
                    <p class="eyebrow">Availability</p>
                    <p class="mt-2 text-lg font-semibold text-slate-900">{{ $product->stock }} unit(s) live</p>
                </div>
                <div class="panel-muted p-4">
                    <p class="eyebrow">Payment Rail</p>
                    <p class="mt-2 text-lg font-semibold text-slate-900">Midtrans Only</p>
                </div>
            </div>

            <p class="leading-7 text-slate-600">{{ $product->description }}</p>

            @auth
                <form method="POST" action="{{ route('cart.store') }}" class="panel-muted flex flex-col gap-4 p-5 sm:flex-row sm:items-center">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="sm:w-36">
                        <label for="quantity" class="mb-2 block text-xs uppercase tracking-[0.24em] text-zinc-500">Quantity</label>
                        <input id="quantity" type="number" name="quantity" min="1" max="{{ max($product->stock, 1) }}" value="1" class="form-input">
                    </div>
                    <button type="submit" class="btn-primary sm:mt-7">Add To Cart</button>
                </form>
            @else
                <div class="panel-muted p-5 text-sm text-slate-600">
                    Login is required to add items to the cart and proceed to checkout.
                    <div class="mt-4 flex gap-3">
                        <a href="{{ route('login') }}" class="btn-secondary">Login</a>
                        <a href="{{ route('register') }}" class="btn-primary">Register</a>
                    </div>
                </div>
            @endauth
        </div>
    </section>

    <section class="mt-10">
        <div class="mb-6">
            <p class="eyebrow">Related Gear</p>
            <h2 class="section-title">Related Products</h2>
        </div>
        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            @foreach($relatedProducts as $relatedProduct)
                @include('partials.product-card', ['product' => $relatedProduct])
            @endforeach
        </div>
    </section>
@endsection
