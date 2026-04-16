@extends('layouts.app')

@section('content')
    <section class="mx-auto max-w-xl">
        <div class="panel">
            <p class="eyebrow">New User</p>
            <h1 class="mb-6 page-title">Create Account</h1>

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="name" class="form-label">Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-input" required>
                </div>
                <div>
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-input" required>
                </div>
                <div>
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" name="password" class="form-input" required>
                </div>
                <div>
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="form-input" required>
                </div>
                <button type="submit" class="btn-primary w-full justify-center">Deploy Account</button>
            </form>
        </div>
    </section>
@endsection
