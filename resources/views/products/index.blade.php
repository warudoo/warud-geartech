@extends('layouts.app')

@section('content')
    <section class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="eyebrow">Store Catalog</p>
            <h1 class="page-title">Product Catalog</h1>
            <p class="mt-2 max-w-2xl text-slate-600">Browse the catalog with simple category filters and quick keyword search.</p>
        </div>
        <form method="GET" action="{{ route('products.index') }}" class="panel flex w-full flex-col gap-4 lg:max-w-3xl lg:flex-row">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search product or SKU" class="form-input lg:flex-1">
            <select name="category" class="form-select lg:w-60">
                <option value="">All categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary justify-center">Filter</button>
        </form>
    </section>

    <section class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        @forelse($products as $product)
            @include('partials.product-card', ['product' => $product])
        @empty
            <div class="panel text-slate-600">No products matched the current filters.</div>
        @endforelse
    </section>

    <div class="mt-8">
        {{ $products->links() }}
    </div>
@endsection
