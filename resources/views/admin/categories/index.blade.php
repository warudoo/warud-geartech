@extends('layouts.app')

@section('content')
    <section class="mb-8 flex items-end justify-between gap-4">
        <div>
            <p class="eyebrow">Admin Catalog</p>
            <h1 class="page-title">Categories</h1>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn-primary">New Category</a>
    </section>

    <div class="panel overflow-hidden">
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
                                <div class="flex justify-end gap-2">
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
