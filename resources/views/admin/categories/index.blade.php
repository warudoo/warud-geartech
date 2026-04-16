@extends('layouts.app')

@section('content')
    <section class="mb-6 flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
        <div>
            <p class="eyebrow">Admin Catalog</p>
            <h1 class="page-title">Manajemen Kategori</h1>
            <p class="mt-3 max-w-3xl text-sm leading-6 text-slate-600">Jaga struktur katalog tetap rapi agar filter storefront dan proses input produk tetap konsisten.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.products.index') }}" class="btn-secondary">Lihat Produk</a>
            <a href="{{ route('admin.categories.create') }}" class="btn-primary">Kategori Baru</a>
        </div>
    </section>

    <div class="admin-shell overflow-hidden">
        <div class="table-shell">
            <table class="min-w-full text-sm">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Products</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->slug }}</td>
                            <td>{{ $category->products_count }}</td>
                            <td>{{ $category->is_active ? 'Active' : 'Hidden' }}</td>
                            <td class="text-right">
                                <div class="flex flex-wrap justify-end gap-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn-secondary">Edit</a>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}">
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
        {{ $categories->links() }}
    </div>
@endsection
