@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto py-12">
    <h1 class="text-2xl font-bold mb-6">
        Lupa Password
    </h1>

    @if (session('status'))
        <div class="mb-4 rounded bg-green-100 p-3 text-green-700">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-4">
            <label class="block mb-2">
                Email
            </label>

            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                class="w-full rounded border p-2"
                required
            >

            @error('email')
                <p class="text-red-500 text-sm mt-1">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <button
            type="submit"
            class="rounded bg-black px-4 py-2 text-white"
        >
            Kirim Link Reset
        </button>
    </form>
</div>
@endsection