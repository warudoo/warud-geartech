@extends('layouts.app')

@section('content')
    <section class="mb-8">
        <p class="eyebrow">Checkout Sequence</p>
        <h1 class="page-title">Checkout</h1>
    </section>

    <div class="grid gap-8 lg:grid-cols-[0.95fr_1.05fr]">
        <aside class="panel h-fit">
            <div class="mb-5">
                <p class="eyebrow">Order Items</p>
                <h2 class="section-title">Order Summary</h2>
            </div>
            <div class="space-y-4">
                @foreach($cartItems as $item)
                    <div class="panel-muted flex items-center justify-between gap-4 p-4">
                        <div>
                            <p class="text-sm uppercase tracking-[0.18em] text-slate-500">{{ $item->product->brand }} • {{ $item->product->sku }}</p>
                            <p class="mt-1 font-semibold text-slate-900">{{ $item->product->name }}</p>
                            <p class="mt-1 text-sm text-slate-600">{{ $item->quantity }} x Rp {{ number_format((float) $item->product->price, 0, ',', '.') }}</p>
                        </div>
                        <p class="font-display text-xl text-red-600">Rp {{ number_format($item->lineTotal(), 0, ',', '.') }}</p>
                    </div>
                @endforeach
            </div>
            <div class="mt-5 border-t border-slate-200 pt-5">
                <div class="flex items-center justify-between text-lg font-semibold text-slate-900">
                    <span>Total</span>
                    <span class="font-display text-3xl text-red-600">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
            </div>
        </aside>

        <div class="panel">
            <p class="eyebrow">Shipping Profile</p>
            <h2 class="mb-6 section-title">Buyer Details</h2>

            <form method="POST" action="{{ route('checkout.store') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="customer_name" class="form-label">Customer Name</label>
                    <input id="customer_name" type="text" name="customer_name" value="{{ old('customer_name', $user->name) }}" class="form-input" required>
                </div>
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="email" class="form-label">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input" required>
                    </div>
                    <div>
                        <label for="phone" class="form-label">Phone</label>
                        <input id="phone" type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-input" required>
                    </div>
                </div>
                <div>
                    <label for="shipping_address" class="form-label">Shipping Address</label>
                    <textarea id="shipping_address" name="shipping_address" rows="5" class="form-textarea" required>{{ old('shipping_address', $user->address) }}</textarea>
                </div>
                <div>
                    <label for="notes" class="form-label">Order Notes</label>
                    <textarea id="notes" name="notes" rows="3" class="form-textarea">{{ old('notes') }}</textarea>
                </div>
                <button type="submit" class="btn-primary w-full justify-center">Create Order And Pay</button>
            </form>
        </div>
    </div>
@endsection
