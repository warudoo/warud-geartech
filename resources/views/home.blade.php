@extends('layouts.app')

@section('content')
    <section class="hero-grid mb-10">
        <div class="panel relative overflow-hidden">
            <div
                class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(220,38,38,0.12),_transparent_50%),radial-gradient(circle_at_bottom_right,_rgba(248,250,252,0.92),_transparent_40%)]">
            </div>
            <div class="relative grid gap-8 lg:grid-cols-[1.3fr_0.7fr]">
                <div class="space-y-6">
                    <p class="eyebrow">Laravel 13 Ecommerce Build</p>
                    <h1 class="page-title max-w-3xl leading-tight">
                        Clean ecommerce storefront with a focused shopping flow.
                    </h1>
                    <p class="max-w-2xl text-base leading-7 text-slate-600 sm:text-lg">
                        Browse products, checkout through Midtrans, and manage inventory from a streamlined Laravel admin
                        dashboard designed for daily operations.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('products.index') }}" class="btn-primary">Browse Products</a>
                        @auth
                            <a href="{{ route('orders.index') }}" class="btn-secondary">Track Orders</a>
                        @else
                            <a href="{{ route('register') }}" class="btn-secondary">Create Account</a>
                        @endauth
                    </div>
                </div>
                <div class="grid gap-4">
                    <div class="stat-card">
                        <p class="eyebrow">Featured Products</p>
                        <p class="font-display text-4xl text-red-600">{{ $featuredProducts->count() }}</p>
                        <p class="text-sm text-slate-600">Highlighted items that are currently promoted on the storefront.
                        </p>
                    </div>
                    <div class="stat-card">
                        <p class="eyebrow">Categories</p>
                        <p class="font-display text-4xl text-slate-900">{{ $categories->count() }}</p>
                        <p class="text-sm text-slate-600">Structured catalog browsing with clean product grouping.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mb-10">
        <div class="mb-6 flex items-end justify-between gap-4">
            <div>
                <p class="eyebrow">Featured Products</p>
                <h2 class="section-title">Featured Picks</h2>
            </div>
            <a href="{{ route('products.index') }}" class="btn-secondary">See Full Catalog</a>
        </div>

        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @forelse($featuredProducts as $product)
                @include('partials.product-card', ['product' => $product])
            @empty
                <div class="panel text-slate-600">No featured products yet.</div>
            @endforelse
        </div>
    </section>

    <section class="space-y-6">
        <div class="panel">
            <p class="eyebrow">Category Map</p>
            <h2 class="mb-4 section-title">Shop By Category</h2>
            <div class="flex gap-4 overflow-x-auto pb-2 snap-x snap-mandatory">
                @foreach ($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                        class="panel-muted shrink-0 w-64 snap-start p-4 transition hover:border-red-500/40 hover:bg-red-500/5">
                        <div class="flex flex-col gap-2 h-full">
                            <p class="text-base font-semibold text-slate-900">
                                {{ $category->name }}
                            </p>
                            <div class="h-20 overflow-y-auto overflow-x-hidden text-sm text-left leading-relaxed text-slate-600 pr-1 break-words no-scrollbar">
                                {{ $category->description }}
                            </div>
                            <div class="mt-auto">
                                <span
                                    class="inline-block rounded-full bg-red-50 px-3 py-1 text-sm font-semibold uppercase tracking-[0.18em] text-red-700">
                                    {{ $category->products_count }} Products
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="panel">
            <div class="mb-6">
                <p class="eyebrow">Latest Additions</p>
                <h2 class="section-title">Latest Inventory</h2>
            </div>
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($latestProducts as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </div>
    </section>
@endsection
