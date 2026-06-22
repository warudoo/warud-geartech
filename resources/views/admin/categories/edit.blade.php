@extends('layouts.app')

@section('content')
    <section class="mx-auto w-full max-w-4xl">
        <div class="mb-6">
            <p class="eyebrow">Admin Catalog</p>
            <h1 class="page-title">Edit Kategori</h1>
            <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600">Perbarui kategori katalog tanpa membuat area form terasa padat dan sesak.</p>
        </div>

        <div class="admin-shell">
            <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="space-y-6">
                @csrf
                @method('PUT')
                @include('admin.categories._form')
                <div class="flex flex-wrap gap-3">
                    <button type="submit" class="btn-primary">Update Category</button>
                    <a href="{{ route('admin.categories.index') }}" class="btn-secondary">Back</a>
                </div>
            </form>
        </div>
    </section>
@endsection
