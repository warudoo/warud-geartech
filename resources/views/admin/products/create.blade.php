@extends('layouts.app')

@section('content')
    <section class="mx-auto w-full max-w-5xl">
        <div class="mb-6">
            <p class="eyebrow">Admin Inventory</p>
            <h1 class="page-title">Tambah Produk</h1>
            <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600">Input produk baru ke katalog admin tanpa membuat halaman form terasa sempit atau terlalu menempel ke tepi layar.</p>
        </div>

        <div class="admin-shell">
            <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @include('admin.products._form')
                <div class="flex flex-wrap gap-3">
                    <button type="submit" class="btn-primary">Save Product</button>
                    <a href="{{ route('admin.products.index') }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </section>
@endsection
