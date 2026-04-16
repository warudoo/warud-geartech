@extends('layouts.app')

@section('content')
    <section class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="eyebrow">Admin Console</p>
            <h1 class="page-title">Dashboard</h1>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.products.index') }}" class="btn-secondary">Manage Products</a>
            <a href="{{ route('admin.orders.index') }}" class="btn-primary">Manage Orders</a>
        </div>
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
            <p class="eyebrow">Users</p>
            <p class="font-display text-4xl text-slate-900">{{ $totalUsers }}</p>
        </div>
        <div class="stat-card">
            <p class="eyebrow">Products</p>
            <p class="font-display text-4xl text-slate-900">{{ $totalProducts }}</p>
        </div>
    </section>

    <section class="grid gap-6 lg:grid-cols-[1fr_1fr]">
        <div class="panel">
            <p class="eyebrow">Recent Orders</p>
            <h2 class="mb-5 section-title">Latest Activity</h2>
            <div class="space-y-3">
                @foreach($recentOrders as $order)
                    <a href="{{ route('admin.orders.show', $order) }}" class="panel-muted flex items-center justify-between gap-4 p-4 transition hover:border-red-500/40">
                        <div>
                            <p class="text-sm uppercase tracking-[0.18em] text-slate-500">{{ $order->order_number }}</p>
                            <p class="mt-1 font-semibold text-slate-900">{{ $order->customer_name }}</p>
                            <p class="text-sm text-slate-600">{{ $order->user->email }}</p>
                        </div>
                        <div class="text-right">
                            <span class="rounded-full px-3 py-1 text-xs uppercase tracking-[0.24em] ring-1 {{ $order->status->badgeClasses() }}">
                                {{ $order->status->label() }}
                            </span>
                            <p class="mt-2 font-display text-xl text-red-600">Rp {{ number_format((float) $order->total, 0, ',', '.') }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="panel">
            <p class="eyebrow">Quick Actions</p>
            <h2 class="mb-5 section-title">Operations</h2>
            <div class="grid gap-4 md:grid-cols-2">
                <a href="{{ route('admin.categories.index') }}" class="panel-muted p-5 transition hover:border-red-500/40">
                    <p class="eyebrow">Catalog</p>
                    <p class="mt-2 text-xl font-semibold text-slate-900">Categories</p>
                    <p class="mt-2 text-sm text-slate-600">Maintain storefront product grouping.</p>
                </a>
                <a href="{{ route('admin.products.index') }}" class="panel-muted p-5 transition hover:border-red-500/40">
                    <p class="eyebrow">Inventory</p>
                    <p class="mt-2 text-xl font-semibold text-slate-900">Products</p>
                    <p class="mt-2 text-sm text-slate-600">Update stock, pricing, and active status quickly.</p>
                </a>
                <a href="{{ route('admin.orders.index') }}" class="panel-muted p-5 transition hover:border-red-500/40">
                    <p class="eyebrow">Fulfillment</p>
                    <p class="mt-2 text-xl font-semibold text-slate-900">Orders</p>
                    <p class="mt-2 text-sm text-slate-600">Advance paid orders through shipment and completion.</p>
                </a>
                <a href="{{ route('admin.reports.index') }}" class="panel-muted p-5 transition hover:border-red-500/40">
                    <p class="eyebrow">Reporting</p>
                    <p class="mt-2 text-xl font-semibold text-slate-900">Sales Reports</p>
                    <p class="mt-2 text-sm text-slate-600">Review revenue, AOV, and low stock warnings.</p>
                </a>
            </div>
        </div>
    </section>
@endsection
