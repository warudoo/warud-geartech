@extends('layouts.app')

@section('content')
    <section class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="eyebrow">Sales Intelligence</p>
            <h1 class="page-title">Report Summary</h1>
        </div>
        <form method="GET" action="{{ route('admin.reports.index') }}" class="panel flex flex-col gap-4 lg:flex-row">
            <input type="date" name="from" value="{{ $from->toDateString() }}" class="form-input">
            <input type="date" name="to" value="{{ $to->toDateString() }}" class="form-input">
            <button type="submit" class="btn-primary justify-center">Apply Range</button>
        </form>
    </section>

    <section class="mb-8 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="stat-card">
            <p class="eyebrow">Gross Revenue</p>
            <p class="font-display text-4xl text-red-600">Rp {{ number_format($summary['gross_revenue'], 0, ',', '.') }}</p>
        </div>
        <div class="stat-card">
            <p class="eyebrow">Paid Orders</p>
            <p class="font-display text-4xl text-slate-900">{{ $summary['paid_orders'] }}</p>
        </div>
        <div class="stat-card">
            <p class="eyebrow">AOV</p>
            <p class="font-display text-4xl text-slate-900">Rp {{ number_format($summary['average_order_value'], 0, ',', '.') }}</p>
        </div>
        <div class="stat-card">
            <p class="eyebrow">Low Stock</p>
            <p class="font-display text-4xl text-slate-900">{{ $summary['low_stock_products'] }}</p>
        </div>
    </section>

    <section class="grid gap-6 lg:grid-cols-[1fr_1fr]">
        <div class="panel">
            <p class="eyebrow">Recent Paid Orders</p>
            <h2 class="mb-5 section-title">Revenue Feed</h2>
            <div class="space-y-3">
                @forelse($summary['recent_paid_orders'] as $order)
                    <div class="panel-muted flex items-center justify-between gap-4 p-4">
                        <div>
                            <p class="font-semibold text-slate-900">{{ $order->order_number }}</p>
                            <p class="text-sm text-slate-600">{{ $order->customer_name }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-display text-xl text-red-600">Rp {{ number_format((float) $order->total, 0, ',', '.') }}</p>
                            <p class="text-sm text-slate-500">{{ $order->paid_at?->format('d M Y') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-sm text-slate-600">No paid orders in this range.</div>
                @endforelse
            </div>
        </div>

        <div class="panel">
            <p class="eyebrow">Daily Sales</p>
            <h2 class="mb-5 section-title">Trend Snapshot</h2>
            <div class="space-y-3">
                @forelse($summary['daily_sales'] as $day)
                    <div class="panel-muted flex items-center justify-between gap-4 p-4">
                        <div>
                            <p class="font-semibold text-slate-900">{{ \Illuminate\Support\Carbon::parse($day->paid_date)->format('d M Y') }}</p>
                            <p class="text-sm text-slate-600">{{ $day->orders_count }} order(s)</p>
                        </div>
                        <p class="font-display text-xl text-red-600">Rp {{ number_format((float) $day->revenue, 0, ',', '.') }}</p>
                    </div>
                @empty
                    <div class="text-sm text-slate-600">No daily sales records in this range.</div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
