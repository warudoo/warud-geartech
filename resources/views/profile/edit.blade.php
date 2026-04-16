@extends('layouts.app')

@section('content')
    <section class="mx-auto max-w-3xl">
        <div class="panel">
            <p class="eyebrow">Account Settings</p>
            <h1 class="mb-6 page-title">Profile</h1>

            <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="name" class="form-label">Name</label>
                        <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input" required>
                    </div>
                    <div>
                        <label for="email" class="form-label">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input" required>
                    </div>
                </div>

                <div>
                    <label for="phone" class="form-label">Phone</label>
                    <input id="phone" type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-input">
                </div>

                <div>
                    <label for="address" class="form-label">Default Address</label>
                    <textarea id="address" name="address" rows="5" class="form-textarea">{{ old('address', $user->address) }}</textarea>
                </div>

                <button type="submit" class="btn-primary">Save Profile</button>
            </form>
        </div>
    </section>
@endsection
