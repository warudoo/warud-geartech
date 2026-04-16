@extends('layouts.app')

@section('content')
    <section class="mb-8">
        <p class="eyebrow">Purchase Log</p>
        <h1 class="page-title">Order History</h1>
    </section>

    <div class="space-y-4">
        @forelse($orders as $order)
            <article class="panel flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <div class="flex flex-wrap items-center gap-3">
                        <h2 class="font-display text-2xl text-slate-900">{{ $order->order_number }}</h2>
                        <span class="rounded-full px-3 py-1 text-xs uppercase tracking-[0.24em] ring-1 {{ $order->status->badgeClasses() }}">
                            {{ $order->status->label() }}
                        </span>
                    </div>
                    <p class="mt-2 text-sm text-slate-500">{{ $order->created_at->format('d M Y H:i') }} • {{ $order->items->count() }} item(s)</p>
                </div>
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <p class="font-display text-2xl text-red-600">Rp {{ number_format((float) $order->total, 0, ',', '.') }}</p>
                    <a href="{{ route('orders.show', $order->order_number) }}" class="btn-secondary">Open Detail</a>
                </div>
            </article>
        @empty
            <div class="panel text-slate-600">No orders yet.</div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $orders->links() }}
    </div>
@endsection
