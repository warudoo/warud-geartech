@extends('layouts.app')

@section('content')
    <section class="mx-auto max-w-3xl">
        <div class="panel">
            <p class="eyebrow">Admin Catalog</p>
            <h1 class="mb-6 page-title">Create Category</h1>
            <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-6">
                @csrf
                @include('admin.categories._form')
                <button type="submit" class="btn-primary">Save Category</button>
            </form>
        </div>
    </section>
@endsection
