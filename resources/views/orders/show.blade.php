@extends('layouts.app')

@section('content')
    <section class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="eyebrow">Order Detail</p>
            <h1 class="page-title">{{ $order->order_number }}</h1>
            <div class="mt-3 flex items-center gap-3">
                <span class="rounded-full px-3 py-1 text-xs uppercase tracking-[0.24em] ring-1 {{ $order->status->badgeClasses() }}">
                    {{ $order->status->label() }}
                </span>
                <span class="text-sm text-slate-500">Placed {{ $order->created_at->format('d M Y H:i') }}</span>
            </div>
        </div>

        @if($order->canBePaid())
            <form method="POST" action="{{ route('orders.payment.store', $order->order_number) }}">
                @csrf
                <button type="submit" class="btn-primary">Pay With Midtrans</button>
            </form>
        @endif
    </section>

    <div class="grid gap-8 lg:grid-cols-[1.05fr_0.95fr]">
        <div class="space-y-4">
            @foreach($order->items as $item)
                <article class="panel flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.18em] text-slate-500">{{ $item->sku }}</p>
                        <h2 class="mt-1 text-xl font-semibold text-slate-900">{{ $item->product_name }}</h2>
                        <p class="mt-2 text-sm text-slate-600">{{ $item->quantity }} x Rp {{ number_format((float) $item->unit_price, 0, ',', '.') }}</p>
                    </div>
                    <p class="font-display text-2xl text-red-600">Rp {{ number_format((float) $item->line_total, 0, ',', '.') }}</p>
                </article>
            @endforeach
        </div>

        <aside class="space-y-4">
            <div class="panel">
                <p class="eyebrow">Customer</p>
                <h2 class="mb-4 section-title">Shipping</h2>
                <div class="space-y-3 text-sm leading-6 text-slate-600">
                    <p><span class="text-slate-500">Name:</span> {{ $order->customer_name }}</p>
                    <p><span class="text-slate-500">Email:</span> {{ $order->email }}</p>
                    <p><span class="text-slate-500">Phone:</span> {{ $order->phone }}</p>
                    <p><span class="text-slate-500">Address:</span><br>{{ $order->shipping_address }}</p>
                    @if($order->notes)
                        <p><span class="text-slate-500">Notes:</span><br>{{ $order->notes }}</p>
                    @endif
                </div>
            </div>

            <div class="panel">
                <p class="eyebrow">Payment</p>
                <h2 class="mb-4 section-title">Summary</h2>
                <div class="space-y-3 text-sm text-slate-600">
                    <div class="flex items-center justify-between">
                        <span>Method</span>
                        <span>Midtrans</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Total</span>
                        <span class="font-display text-2xl text-red-600">Rp {{ number_format((float) $order->total, 0, ',', '.') }}</span>
                    </div>
                    @if($order->paid_at)
                        <div class="flex items-center justify-between">
                            <span>Paid At</span>
                            <span>{{ $order->paid_at->format('d M Y H:i') }}</span>
                        </div>
                    @endif
                    @php($payment = $order->payment->first())
                    @if($payment?->payment_type)
                        <div class="flex items-center justify-between">
                            <span>Channel</span>
                            <span>{{ str($payment->payment_type)->title() }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </aside>
    </div>
@endsection
