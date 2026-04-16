@extends('layouts.app')

@section('content')
    <section class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="eyebrow">Admin Fulfillment</p>
            <h1 class="page-title">{{ $order->order_number }}</h1>
            <div class="mt-3 flex items-center gap-3">
                <span class="rounded-full px-3 py-1 text-xs uppercase tracking-[0.24em] ring-1 {{ $order->status->badgeClasses() }}">
                    {{ $order->status->label() }}
                </span>
                <span class="text-sm text-slate-500">{{ $order->created_at->format('d M Y H:i') }}</span>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="panel flex flex-col gap-3 sm:flex-row sm:items-end">
            @csrf
            @method('PATCH')
            <div>
                <label for="status" class="form-label">Update Status</label>
                <select id="status" name="status" class="form-select sm:w-56">
                    @foreach($availableStatuses as $status)
                        <option value="{{ $status->value }}">{{ $status->label() }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-primary">Apply</button>
        </form>
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
                <p class="eyebrow">Buyer</p>
                <h2 class="mb-4 section-title">Order Meta</h2>
                <div class="space-y-3 text-sm leading-6 text-slate-600">
                    <p><span class="text-slate-500">Customer:</span> {{ $order->customer_name }}</p>
                    <p><span class="text-slate-500">Email:</span> {{ $order->email }}</p>
                    <p><span class="text-slate-500">Phone:</span> {{ $order->phone }}</p>
                    <p><span class="text-slate-500">Address:</span><br>{{ $order->shipping_address }}</p>
                    <p><span class="text-slate-500">Paid at:</span> {{ $order->paid_at?->format('d M Y H:i') ?? 'Not paid yet' }}</p>
                    <p><span class="text-slate-500">Stock deducted:</span> {{ $order->stock_deducted_at?->format('d M Y H:i') ?? 'No' }}</p>
                </div>
            </div>

            <div class="panel">
                <p class="eyebrow">Payment Rail</p>
                <h2 class="mb-4 section-title">Midtrans</h2>
                @php($payment = $order->payment->first())
                <div class="space-y-3 text-sm text-slate-600">
                    <p><span class="text-slate-500">Transaction status:</span> {{ $payment?->transaction_status ?? 'N/A' }}</p>
                    <p><span class="text-slate-500">Payment type:</span> {{ $payment?->payment_type ?? 'N/A' }}</p>
                    <p><span class="text-slate-500">Last callback:</span> {{ $payment?->last_callback_at?->format('d M Y H:i') ?? 'Never' }}</p>
                </div>
            </div>
        </aside>
    </div>
@endsection
