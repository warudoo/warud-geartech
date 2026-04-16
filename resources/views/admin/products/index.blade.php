@extends('layouts.app')

@section('content')
    <section class="mb-6 flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
        <div>
            <p class="eyebrow">Admin Inventory</p>
            <h1 class="page-title">Manajemen Produk</h1>
            <p class="mt-3 max-w-3xl text-sm leading-6 text-slate-600">Kelola katalog, stok, dan status aktif produk dari halaman yang lebih lega dan lebih mudah dipindai saat operasional.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.categories.index') }}" class="btn-secondary">Lihat Kategori</a>
            <a href="{{ route('admin.products.create') }}" class="btn-primary">Produk Baru</a>
        </div>
    </section>

    <section class="mb-6 admin-toolbar">
        <form method="GET" action="{{ route('admin.products.index') }}" class="grid gap-4 xl:grid-cols-[minmax(0,1fr)_240px_auto]">
            <div>
                <label for="search" class="form-label">Search</label>
                <input id="search" type="text" name="search" value="{{ request('search') }}" placeholder="Search name, SKU, or brand" class="form-input">
            </div>
            <div>
                <label for="category" class="form-label">Category</label>
                <select id="category" name="category" class="form-select">
                    <option value="">All categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected((string) request('category') === (string) $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-wrap gap-3 xl:self-end">
                <button type="submit" class="btn-primary">Apply</button>
                <a href="{{ route('admin.products.index') }}" class="btn-secondary">Reset</a>
            </div>
        </form>
    </section>

    <div class="admin-shell overflow-hidden">
        <div class="table-shell">
            <table class="min-w-full text-sm">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Brand</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="h-14 w-14 overflow-hidden rounded-2xl border border-slate-200 bg-slate-100">
                                        @if($product->featured_image || $product->image_url)
                                            <img src="{{ $product->featured_image ?: $product->image_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ $product->name }}</p>
                                        <p class="text-xs uppercase tracking-[0.24em] text-slate-500">{{ $product->sku }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $product->category->name }}</td>
                            <td>{{ $product->brand }}</td>
                            <td>Rp {{ number_format((float) $product->price, 0, ',', '.') }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.products.stock.update', $product) }}" class="flex flex-wrap items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="stock" min="0" value="{{ $product->stock }}" class="w-24 rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-700 outline-none focus:border-red-500/50">
                                    <button type="submit" class="btn-secondary">Save</button>
                                </form>
                            </td>
                            <td>
                                <div class="flex flex-col gap-2">
                                    <span class="inline-flex w-fit rounded-full px-3 py-1 text-xs font-semibold {{ $product->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    @if($product->featured)
                                        <span class="inline-flex w-fit rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">Featured</span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-right">
                                <div class="flex flex-wrap justify-end gap-2">
                                    <form method="POST" action="{{ route('admin.products.status.update', $product) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="is_active" value="{{ $product->is_active ? 0 : 1 }}">
                                        <button type="submit" class="btn-secondary">{{ $product->is_active ? 'Deactivate' : 'Activate' }}</button>
                                    </form>
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn-secondary">Edit</a>
                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger">Delete</button>
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
