@extends('layouts.app')

@section('content')
    <section class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="eyebrow">Admin Fulfillment</p>
            <h1 class="page-title">Orders</h1>
        </div>
        <form method="GET" action="{{ route('admin.orders.index') }}" class="panel flex flex-col gap-4 lg:flex-row">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search order/customer/email" class="form-input lg:w-72">
            <select name="status" class="form-select lg:w-56">
                <option value="">All statuses</option>
                @foreach(\App\Enums\OrderStatus::cases() as $status)
                    <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->label() }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary justify-center">Filter</button>
        </form>
    </section>

    <div class="space-y-4">
        @foreach($orders as $order)
            <article class="panel flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <div class="flex flex-wrap items-center gap-3">
                        <h2 class="font-display text-2xl text-slate-900">{{ $order->order_number }}</h2>
                        <span class="rounded-full px-3 py-1 text-xs uppercase tracking-[0.24em] ring-1 {{ $order->status->badgeClasses() }}">
                            {{ $order->status->label() }}
                        </span>
                    </div>
                    <p class="mt-2 text-sm text-slate-500">{{ $order->customer_name }} • {{ $order->email }}</p>
                </div>
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <p class="font-display text-2xl text-red-600">Rp {{ number_format((float) $order->total, 0, ',', '.') }}</p>
                    <a href="{{ route('admin.orders.show', $order) }}" class="btn-secondary">Manage</a>
                </div>
            </article>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $orders->links() }}
    </div>
@endsection
