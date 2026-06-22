@extends('layouts.app')

@section('content')
    <section class="mx-auto w-full max-w-5xl">
        <div class="mb-6">
            <p class="eyebrow">Admin Inventory</p>
            <h1 class="page-title">Edit Produk</h1>
            <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600">Perbarui data produk dengan layout form yang lebih lega dan tombol aksi yang tidak saling berhimpitan.</p>
        </div>

        <div class="admin-shell">
            <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                @include('admin.products._form')
                <div class="flex flex-wrap gap-3">
                    <button type="submit" class="btn-primary">Update Product</button>
                    <a href="{{ route('admin.products.index') }}" class="btn-secondary">Back</a>
                </div>
            </form>
        </div>
    </section>
@endsection
