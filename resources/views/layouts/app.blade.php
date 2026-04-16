<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'GearTech Store' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@php
    $viewer = auth()->user();
    $isAdminUser = $viewer?->isAdmin() ?? false;
    $isBuyerUser = $viewer?->isBuyer() ?? false;
    $isAdminArea = request()->routeIs('admin.*');
    $brandHome = $isAdminUser ? route('admin.dashboard') : route('home');
@endphp
<body class="min-h-screen text-slate-800">
    <div class="min-h-screen bg-grid">
        <header class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/90 backdrop-blur">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-4 py-4 sm:px-6 lg:px-8">
                <a href="{{ $brandHome }}" class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl border border-red-200 bg-red-50 shadow-glow">
                        <span class="font-display text-lg font-bold tracking-[0.12em] text-red-700">GT</span>
                    </div>
                    <div>
                        <p class="font-display text-lg font-semibold tracking-tight text-slate-900">Warud Geartech</p>
                        <p class="text-xs font-medium uppercase tracking-[0.18em] text-slate-500">
                            {{ $isAdminUser ? 'Admin Operations' : 'Gaming Peripheral Store' }}
                        </p>
                    </div>
                </a>

                <button type="button" class="nav-toggle inline-flex items-center rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-700 lg:hidden">
                    Menu
                </button>

                <nav class="nav-menu hidden items-center gap-3 text-sm font-medium lg:flex">
                    @auth
                        @if($isAdminUser)
                            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'nav-link-active' : '' }}">Dashboard</a>
                            <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'nav-link-active' : '' }}">Products</a>
                            <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'nav-link-active' : '' }}">Categories</a>
                            <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'nav-link-active' : '' }}">Orders</a>
                            <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'nav-link-active' : '' }}">Reports</a>
                            <span class="inline-flex items-center rounded-full border border-red-200 bg-red-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-red-700">
                                Admin Only
                            </span>
                        @elseif($isBuyerUser)
                            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'nav-link-active' : '' }}">Home</a>
                            <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'nav-link-active' : '' }}">Products</a>
                            <a href="{{ route('cart.index') }}" class="nav-link {{ request()->routeIs('cart.*') ? 'nav-link-active' : '' }}">
                                Cart
                                <span class="ml-2 rounded-full bg-red-50 px-2 py-0.5 text-xs text-red-700">
                                    {{ $viewer->cartItems()->sum('quantity') }}
                                </span>
                            </a>
                            <a href="{{ route('orders.index') }}" class="nav-link {{ request()->routeIs('orders.*') ? 'nav-link-active' : '' }}">Orders</a>
                            <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'nav-link-active' : '' }}">Profile</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn-secondary">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'nav-link-active' : '' }}">Home</a>
                        <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'nav-link-active' : '' }}">Products</a>
                        <a href="{{ route('login') }}" class="btn-secondary">Login</a>
                        <a href="{{ route('register') }}" class="btn-primary">Register</a>
                    @endauth
                </nav>
            </div>

            <nav class="nav-panel mx-auto hidden max-w-7xl px-4 pb-4 sm:px-6 lg:hidden">
                <div class="panel-muted flex flex-col gap-2 p-3 text-sm">
                    @auth
                        @if($isAdminUser)
                            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'nav-link-active' : '' }}">Dashboard</a>
                            <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'nav-link-active' : '' }}">Products</a>
                            <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'nav-link-active' : '' }}">Categories</a>
                            <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'nav-link-active' : '' }}">Orders</a>
                            <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'nav-link-active' : '' }}">Reports</a>
                        @elseif($isBuyerUser)
                            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'nav-link-active' : '' }}">Home</a>
                            <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'nav-link-active' : '' }}">Products</a>
                            <a href="{{ route('cart.index') }}" class="nav-link {{ request()->routeIs('cart.*') ? 'nav-link-active' : '' }}">Cart</a>
                            <a href="{{ route('orders.index') }}" class="nav-link {{ request()->routeIs('orders.*') ? 'nav-link-active' : '' }}">Orders</a>
                            <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'nav-link-active' : '' }}">Profile</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn-secondary w-full justify-center">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'nav-link-active' : '' }}">Home</a>
                        <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'nav-link-active' : '' }}">Products</a>
                        <a href="{{ route('login') }}" class="btn-secondary w-full justify-center">Login</a>
                        <a href="{{ route('register') }}" class="btn-primary w-full justify-center">Register</a>
                    @endauth
                </div>
            </nav>

            @if($isAdminUser)
                <div class="border-t border-slate-200/80 bg-white/75">
                    <div class="mx-auto flex max-w-[1440px] flex-col gap-3 px-4 py-3 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8 xl:px-10">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-red-600/80">Admin Workspace</p>
                            <p class="mt-1 text-sm text-slate-600">Akses buyer dinonaktifkan untuk akun admin. Gunakan panel ini untuk operasional harian.</p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('admin.dashboard') }}" class="admin-subnav-link {{ request()->routeIs('admin.dashboard') ? 'admin-subnav-link-active' : '' }}">Dashboard</a>
                            <a href="{{ route('admin.products.index') }}" class="admin-subnav-link {{ request()->routeIs('admin.products.*') ? 'admin-subnav-link-active' : '' }}">Products</a>
                            <a href="{{ route('admin.categories.index') }}" class="admin-subnav-link {{ request()->routeIs('admin.categories.*') ? 'admin-subnav-link-active' : '' }}">Categories</a>
                            <a href="{{ route('admin.orders.index') }}" class="admin-subnav-link {{ request()->routeIs('admin.orders.*') ? 'admin-subnav-link-active' : '' }}">Orders</a>
                            <a href="{{ route('admin.reports.index') }}" class="admin-subnav-link {{ request()->routeIs('admin.reports.*') ? 'admin-subnav-link-active' : '' }}">Reports</a>
                        </div>
                    </div>
                </div>
            @endif
        </header>

        @include('partials.flash')

        <main class="{{ $isAdminArea ? 'mx-auto w-full max-w-[1440px] px-4 py-6 sm:px-6 lg:px-8 xl:px-10' : 'mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8' }}">
            @yield('content')
        </main>
    </div>
</body>
</html>
