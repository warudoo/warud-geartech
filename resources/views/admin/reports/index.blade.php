@extends('layouts.app')

@section('content')
    <section class="mb-6 flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
        <div>
            <p class="eyebrow">Sales Intelligence</p>
            <h1 class="page-title">Ringkasan Laporan</h1>
            <p class="mt-3 max-w-3xl text-sm leading-6 text-slate-600">Tinjau performa penjualan, order paid, dan tren harian
                dengan layout yang lebih padat dan mudah dipakai saat evaluasi operasional.</p>
        </div>
        <form method="GET" action="{{ route('admin.reports.index') }}"
            class="admin-toolbar flex flex-col gap-3 sm:flex-row sm:items-end sm:gap-4">

            {{-- DATE FROM --}}
            <div class="flex flex-col w-full sm:w-auto">
                <label class="text-xs text-slate-500 mb-1">From</label>
                <input type="date" name="from" value="{{ $from->toDateString() }}" class="form-input w-full sm:w-auto">
            </div>

            {{-- DATE TO --}}
            <div class="flex flex-col w-full sm:w-auto">
                <label class="text-xs text-slate-500 mb-1">To</label>
                <input type="date" name="to" value="{{ $to->toDateString() }}" class="form-input w-full sm:w-auto">
            </div>

            {{-- ACTION --}}
            <div class="flex gap-2 w-full sm:w-auto sm:ml-auto">
                <button type="submit" class="btn-primary w-full sm:w-auto justify-center">
                    Apply Range
                </button>

                <a href="{{ route('admin.reports.index') }}" class="btn-secondary w-full sm:w-auto text-center">
                    Reset
                </a>
            </div>

        </form>
    </section>

    <section class="mb-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="admin-stat-card">
            <p class="eyebrow">Gross Revenue</p>
            <p class="admin-stat-value text-red-600">Rp {{ number_format($summary['gross_revenue'], 0, ',', '.') }}</p>
        </div>
        <div class="admin-stat-card">
            <p class="eyebrow">Paid Orders</p>
            <p class="admin-stat-value">{{ $summary['paid_orders'] }}</p>
        </div>
        <div class="admin-stat-card">
            <p class="eyebrow">AOV</p>
            <p class="admin-stat-value">Rp {{ number_format($summary['average_order_value'], 0, ',', '.') }}</p>
        </div>
        <div class="admin-stat-card">
            <p class="eyebrow">Low Stock</p>
            <p class="admin-stat-value">{{ $summary['low_stock_products'] }}</p>
        </div>
    </section>

    <section class="grid gap-6 xl:grid-cols-[1fr_1fr]">
        <div class="admin-shell flex flex-col max-h-[420px]">
            <p class="eyebrow">Recent Paid Orders</p>
            <h2 class="mb-5 admin-section-heading">Revenue Feed</h2>

            <div class="space-y-3 overflow-y-auto pr-2">
                @forelse($summary['recent_paid_orders'] as $order)
                    <div class="admin-list-card flex items-center justify-between gap-4">
                        <div>
                            <p class="font-semibold text-slate-900 line-clamp-2">{{ $order->order_number }}</p>
                            <p class="text-sm text-slate-600">{{ $order->customer_name }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-display text-xl text-red-600">Rp
                                {{ number_format((float) $order->total, 0, ',', '.') }}</p>
                            <p class="text-sm text-slate-500">{{ $order->paid_at?->format('d M Y') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="admin-list-card text-sm text-slate-600">No paid orders in this range.</div>
                @endforelse
            </div>
        </div>

        <div class="admin-shell flex flex-col max-h-[420px]">
            <p class="eyebrow">Daily Sales</p>
            <h2 class="mb-5 admin-section-heading">Trend Snapshot</h2>

            <div class="space-y-3 overflow-y-auto pr-2">
                @forelse($summary['daily_sales'] as $day)
                    <div class="admin-list-card flex items-center justify-between gap-4">
                        <div>
                            <p class="font-semibold text-slate-900">
                                {{ \Illuminate\Support\Carbon::parse($day->paid_date)->format('d M Y') }}
                            </p>
                            <p class="text-sm text-slate-600">{{ $day->orders_count }} order(s)</p>
                        </div>
                        <p class="font-display text-xl text-red-600">Rp
                            {{ number_format((float) $day->revenue, 0, ',', '.') }}</p>
                    </div>
                @empty
                    <div class="admin-list-card text-sm text-slate-600">No daily sales records in this range.</div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
