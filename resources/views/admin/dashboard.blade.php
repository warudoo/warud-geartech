@extends('layouts.app')

@section('content')
    <section class="mb-6">
        <div>
            <p class="eyebrow">Admin Console</p>
            <h1 class="page-title">Dashboard Operasional</h1>
            <p class="mt-3 max-w-3xl text-sm leading-6 text-slate-600">
                Pantau order terbaru, kondisi stok, dan ringkasan performa toko dari satu workspace yang lebih rapi untuk operasional harian Warud Geartech.
            </p>
        </div>
    </section>

    <section class="mb-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="admin-stat-card">
            <p class="eyebrow">Gross Revenue</p>
            <p class="admin-stat-value text-red-600">Rp {{ number_format($summary['gross_revenue'], 0, ',', '.') }}</p>
            <p class="mt-3 text-sm text-slate-500">Akumulasi order yang sudah masuk status paid hingga completed.</p>
        </div>
        <div class="admin-stat-card">
            <p class="eyebrow">Paid Orders</p>
            <p class="admin-stat-value">{{ $summary['paid_orders'] }}</p>
            <p class="mt-3 text-sm text-slate-500">Order aktif yang sudah menghasilkan pembayaran valid.</p>
        </div>
        <div class="admin-stat-card">
            <p class="eyebrow">Buyer Accounts</p>
            <p class="admin-stat-value">{{ $totalBuyers }}</p>
            <p class="mt-3 text-sm text-slate-500">Total akun customer yang bisa memakai flow belanja normal.</p>
        </div>
        <div class="admin-stat-card">
            <p class="eyebrow">Active Products</p>
            <p class="admin-stat-value">{{ $activeProducts }} <span class="text-lg text-slate-400">/ {{ $totalProducts }}</span></p>
            <p class="mt-3 text-sm text-slate-500">Produk aktif dibanding keseluruhan katalog yang tersedia.</p>
        </div>
    </section>

    <section class="grid gap-6 xl:grid-cols-[minmax(0,1.2fr)_minmax(320px,0.8fr)]">
        <div class="admin-shell">
            <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="eyebrow">Recent Orders</p>
                    <h2 class="admin-section-heading">Aktivitas Terbaru</h2>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="btn-secondary w-full sm:w-auto">Lihat Semua Order</a>
            </div>
            <div class="space-y-3">
                @forelse($recentOrders as $order)
                    <a href="{{ route('admin.orders.show', $order) }}" class="admin-list-card flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div class="min-w-0">
                            <p class="text-xs uppercase tracking-[0.18em] text-slate-500">{{ $order->order_number }}</p>
                            <p class="mt-1 font-semibold text-slate-900">{{ $order->customer_name }}</p>
                            <p class="mt-1 text-sm text-slate-600">{{ $order->user->email }}</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-3 lg:text-right">
                            <span class="rounded-full px-3 py-1 text-xs uppercase tracking-[0.24em] ring-1 {{ $order->status->badgeClasses() }}">
                                {{ $order->status->label() }}
                            </span>
                            <p class="font-display text-xl text-red-600">Rp {{ number_format((float) $order->total, 0, ',', '.') }}</p>
                        </div>
                    </a>
                @empty
                    <div class="admin-list-card text-sm text-slate-600">
                        Belum ada order terbaru untuk ditampilkan.
                    </div>
                @endforelse
            </div>
        </div>

        <div>
            <div class="admin-shell">
                <div class="mb-5 flex items-end justify-between gap-3">
                    <div>
                        <p class="eyebrow">Low Stock Watch</p>
                        <h2 class="admin-section-heading">Produk Perlu Perhatian</h2>
                    </div>
                    <span class="rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-amber-700">
                        {{ $summary['low_stock_products'] }} item
                    </span>
                </div>

                <div class="space-y-3">
                    @forelse($lowStockProducts as $product)
                        <a href="{{ route('admin.products.edit', $product) }}" class="admin-list-card flex items-center justify-between gap-4">
                            <div>
                                <p class="font-semibold text-slate-900">{{ $product->name }}</p>
                                <p class="mt-1 text-sm text-slate-600">{{ $product->category->name }} / {{ $product->brand }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Stock</p>
                                <p class="font-display text-2xl {{ $product->stock <= 2 ? 'text-rose-600' : 'text-amber-600' }}">{{ $product->stock }}</p>
                            </div>
                        </a>
                    @empty
                        <div class="admin-list-card text-sm text-slate-600">
                            Tidak ada produk dengan stok kritis saat ini.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
@endsection
