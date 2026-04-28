@extends('layouts.app')

@section('content')
    <section class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">

        <div>
            <p class="eyebrow">Admin Inventory</p>
            <h1 class="page-title">Manajemen Produk</h1>
            <p class="mt-2 text-sm text-slate-600 max-w-xl">
                Kelola katalog, stok, dan status produk dengan lebih efisien.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
            <a href="{{ route('admin.categories.index') }}" class="btn-secondary w-full sm:w-auto text-center">
                Lihat Kategori
            </a>
            <a href="{{ route('admin.products.create') }}" class="btn-primary w-full sm:w-auto text-center">
                Tambah Produk
            </a>
        </div>

    </section>

    <section class="mb-6 admin-toolbar">
        <form method="GET" action="{{ route('admin.products.index') }}"
            class="flex flex-col gap-3 lg:grid lg:grid-cols-[1fr_200px_auto] lg:items-end">

            <div>
                <label class="form-label">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" class="form-input w-full">
            </div>

            <div>
                <label class="form-label">Category</label>
                <select name="category" class="form-select w-full">
                    <option value="">All</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(request('category') == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button class="btn-primary w-full lg:w-auto">Apply</button>
                <a href="{{ route('admin.products.index') }}" class="btn-secondary w-full lg:w-auto text-center">
                    Reset
                </a>
            </div>

        </form>
    </section>

    {{-- Mobile View Card --}}
    <div class="lg:hidden flex flex-col gap-4">

        @foreach ($products as $product)
            <div class="panel overflow-hidden flex flex-col">

                {{-- IMAGE (FULL WIDTH, HERO STYLE) --}}
                <div class="aspect-[4/3] w-full overflow-hidden bg-slate-100">
                    <img src="{{ $product->display_image_url }}" alt="{{ $product->name }}"
                        class="h-full w-full object-cover">
                </div>

                {{-- CONTENT --}}
                <div class="p-4 flex flex-col gap-3">

                    {{-- TITLE --}}
                    <div class="text-center">
                        <h3 class="font-semibold text-base leading-tight line-clamp-2">
                            {{ $product->name }}
                        </h3>
                        <p class="text-xs text-slate-500 mt-1">
                            {{ $product->brand }} • {{ $product->sku }}
                        </p>
                        <p class="text-xs text-slate-500">
                            {{ $product->category->name }}
                        </p>
                    </div>

                    {{-- PRICE --}}
                    <div class="text-center">
                        <p class="text-xs text-slate-500">Price</p>
                        <p class="text-lg font-semibold text-red-600">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>
                    </div>

                    {{-- INFO GRID --}}
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div class="text-center">
                            <p class="text-xs text-slate-500">Brand</p>
                            <p>{{ $product->brand }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-slate-500">Stock</p>
                            <p>{{ $product->stock }}</p>
                        </div>
                    </div>

                    {{-- STOCK INPUT --}}
                    <form method="POST" action="{{ route('admin.products.stock.update', $product) }}" class="flex gap-2">
                        @csrf @method('PATCH')

                        <input type="number" name="stock" value="{{ $product->stock }}" class="form-input">

                        <button class="btn-secondary px-4 w-full">Save</button>
                    </form>

                    {{-- STATUS --}}
                    <div class="flex justify-center gap-2 flex-wrap">
                        <span class="badge w-full {{ $product->is_active ? 'badge-success' : 'badge-muted' }}">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>

                        @if ($product->featured)
                            <span class="badge w-full badge-danger">Featured</span>
                        @endif
                    </div>

                    {{-- ACTION --}}
                    <div class="flex flex-col gap-2 mt-2">

                        <form method="POST" action="{{ route('admin.products.status.update', $product) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="is_active" value="{{ $product->is_active ? 0 : 1 }}">
                            <button class="btn-secondary w-full">
                                {{ $product->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>

                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('admin.products.edit', $product) }}"
                                class="btn-secondary w-full text-center">
                                Edit
                            </a>

                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}">
                                @csrf @method('DELETE')
                                <button class="btn-danger w-full">
                                    Delete
                                </button>
                            </form>
                        </div>

                    </div>

                </div>

            </div>
        @endforeach

    </div>

    {{-- Desktop View Table --}}
    <div class="hidden lg:block admin-shell overflow-hidden">
        <div class="table-shell">
            <table class="w-full text-sm ">

                <thead>
                    <tr class="items-center justify-center text-center">
                        <th>Image</th>
                        <th class="text-center">Product</th>
                        <th class="text-center">Meta</th>
                        <th class="text-center">Price</th>
                        <th class="text-center">Status</th>
                        <th class="w-[140px] text-center">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($products as $product)
                        <tr class="align-top">

                            {{-- IMAGE --}}
                            <td>
                                <div class="h-14 w-14 overflow-hidden rounded-xl border bg-slate-100">
                                    <img src="{{ $product->display_image_url }}" class="h-full w-full object-cover">
                                </div>
                            </td>

                            {{-- PRODUCT --}}
                            <td class="max-w-[260px]">
                                <p class="font-semibold text-slate-900 line-clamp-2">
                                    {{ $product->name }}
                                </p>

                                <p class="text-xs text-slate-500 mt-1">
                                    {{ $product->sku }}
                                </p>
                            </td>

                            {{-- META (category + brand) --}}
                            <td class="text-sm text-slate-600">
                                <p class="font-semibold">{{ $product->brand }}</p>
                                <p class="text-xs text-slate-500">{{ $product->category->name }}</p>
                            </td>

                            {{-- PRICE --}}
                            <td class="font-semibold text-red-600">
                                Rp {{ number_format((float) $product->price, 0, ',', '.') }}
                            </td>

                            {{-- STATUS --}}
                            <td>
                                <div class="flex flex-col gap-2 items-center">
                                    <span class="badge {{ $product->is_active ? 'badge-success' : 'badge-muted' }} w-full">
                                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                                    </span>

                                    @if ($product->featured)
                                        <span class="badge badge-danger text-center w-full">Featured</span>
                                    @endif
                                </div>
                            </td>

                            {{-- ACTION --}}
                            <td class="whitespace-nowrap">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn-secondary w-full">
                                        Edit
                                    </a>

                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}">
                                        @csrf @method('DELETE')
                                        <button class="btn-danger w-full">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>

    <div class="mt-8">
        {{ $products->links() }}
    </div>
@endsection
