@extends('layouts.app')

@section('content')
    <section class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">

        <div>
            <p class="eyebrow">Admin Catalog</p>
            <h1 class="page-title">Manajemen Kategori</h1>
            <p class="mt-2 text-sm text-slate-600 max-w-xl">
                Kelola kategori dan gunakan pencarian cepat.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
            <a href="{{ route('admin.products.index') }}" class="btn-secondary w-full sm:w-auto text-center">
                Lihat Produk
            </a>

            <a href="{{ route('admin.categories.create') }}" class="btn-primary w-full sm:w-auto text-center">
                Tambah Kategori
            </a>
        </div>

    </section>

    <section class="mb-6 admin-toolbar">
        <form method="GET" action="{{ route('admin.categories.index') }}"
            class="flex flex-col gap-3 sm:flex-row sm:items-end">

            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search category..."
                class="form-input w-full sm:w-80">

            <div class="flex gap-2">
                <button class="btn-primary w-full sm:w-auto">Search</button>

                <a href="{{ route('admin.categories.index') }}" class="btn-secondary w-full sm:w-auto text-center">
                    Reset
                </a>
            </div>

        </form>
    </section>

    {{-- Mobile View Card --}}
    <div class="lg:hidden flex flex-col gap-4">

        @foreach ($categories as $category)
            <div class="panel p-4 flex flex-col gap-3">

                {{-- TITLE --}}
                <div>
                    <h3 class="font-semibold text-base">
                        {{ $category->name }}
                    </h3>
                    <p class="text-xs text-slate-500">
                        SLUG: {{ $category->slug }}
                    </p>
                </div>

                {{-- INFO --}}
                <div class="grid grid-cols-2 gap-3 text-sm">

                    <div>
                        <p class="text-xs text-slate-500">Products</p>
                        <p>{{ $category->products_count }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-500">Status</p>
                        <span class="badge w-full mt-1 {{ $category->is_active ? 'badge-success' : 'badge-muted' }}">
                            {{ $category->is_active ? 'Active' : 'Hidden' }}
                        </span>
                    </div>

                </div>

                {{-- ACTION --}}
                <div class="grid grid-cols-2 gap-2">

                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn-secondary w-full text-center">
                        Edit
                    </a>

                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}">
                        @csrf
                        @method('DELETE')

                        <button class="btn-danger w-full">
                            Delete
                        </button>
                    </form>

                </div>

            </div>
        @endforeach

    </div>

    <div class="hidden lg:block admin-shell overflow-hidden">
        <div class="table-shell overflow-x-auto">
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
                    @foreach ($categories as $category)
                        <tr>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->slug }}</td>
                            <td>{{ $category->products_count }}</td>
                            <td>
                                <span class="badge {{ $category->is_active ? 'badge-success' : 'badge-muted' }}">
                                    {{ $category->is_active ? 'Active' : 'Hidden' }}
                                </span>
                            </td>
                            <td class="text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}"
                                        class="btn-secondary">Edit</a>

                                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}">
                                        @csrf @method('DELETE')
                                        <button class="btn-danger">Delete</button>
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
