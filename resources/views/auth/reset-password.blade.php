@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto py-12">
    <h1 class="text-2xl font-bold mb-6">
        Reset Password
    </h1>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <input
            type="hidden"
            name="token"
            value="{{ request()->route('token') }}"
        >

        <div class="mb-4">
            <label>Email</label>

            <input
                type="email"
                name="email"
                value="{{ request('email') }}"
                class="w-full rounded border p-2"
                required
            >
        </div>

        <div class="mb-4">
            <label>Password Baru</label>

            <input
                type="password"
                name="password"
                class="w-full rounded border p-2"
                required
            >
        </div>

        <div class="mb-4">
            <label>Konfirmasi Password</label>

            <input
                type="password"
                name="password_confirmation"
                class="w-full rounded border p-2"
                required
            >
        </div>

        <button
            type="submit"
            class="rounded bg-black px-4 py-2 text-white"
        >
            Simpan Password
        </button>
    </form>
</div>
@endsection