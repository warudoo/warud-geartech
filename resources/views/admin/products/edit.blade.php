@extends('layouts.app')

@section('content')
    <section class="mx-auto max-w-4xl">
        <div class="panel">
            <p class="eyebrow">Admin Inventory</p>
            <h1 class="mb-6 page-title">Edit Product</h1>
            <form method="POST" action="{{ route('admin.products.update', $product) }}" class="space-y-6">
                @csrf
                @method('PUT')
                @include('admin.products._form')
                <button type="submit" class="btn-primary">Update Product</button>
            </form>
        </div>
    </section>
@endsection
