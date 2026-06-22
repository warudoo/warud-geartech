@extends('layouts.app')

@section('content')
    <section class="mx-auto max-w-xl">
        <div class="panel">
            <p class="eyebrow">Access Portal</p>
            <h1 class="mb-6 page-title">Login</h1>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-input" required>
                </div>
                <div>
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" name="password" class="form-input" required>
                </div>
                <button type="submit" class="btn-primary w-full justify-center">Login</button>
                <a href="{{ route('password.request') }}">
                    <div class="btn-danger w-full justify-center">Lupa password ?</div>
                </a>
            </form>
        </div>
    </section>
@endsection
