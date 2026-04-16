<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'GearTech Store' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen text-slate-800">
    <div class="min-h-screen bg-grid">
        <header class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/90 backdrop-blur">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-4 py-4 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl border border-red-200 bg-red-50 shadow-glow">
                        <span class="font-display text-lg font-bold tracking-[0.12em] text-red-700">GT</span>
                    </div>
                    <div>
                        <p class="font-display text-lg font-semibold tracking-tight text-slate-900">GearTech</p>
                        <p class="text-xs font-medium uppercase tracking-[0.18em] text-slate-500">Modern Ecommerce</p>
                    </div>
                </a>

                <button type="button" class="nav-toggle inline-flex items-center rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-700 lg:hidden">
                    Menu
                </button>

                <nav class="nav-menu hidden items-center gap-3 text-sm font-medium lg:flex">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'nav-link-active' : '' }}">Home</a>
                    <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'nav-link-active' : '' }}">Products</a>

                    @auth
                        <a href="{{ route('cart.index') }}" class="nav-link {{ request()->routeIs('cart.*') ? 'nav-link-active' : '' }}">
                            Cart
                            <span class="ml-2 rounded-full bg-red-50 px-2 py-0.5 text-xs text-red-700">
                                {{ auth()->user()->cartItems()->sum('quantity') }}
                            </span>
                        </a>
                        <a href="{{ route('orders.index') }}" class="nav-link {{ request()->routeIs('orders.*') ? 'nav-link-active' : '' }}">Orders</a>
                        <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'nav-link-active' : '' }}">Profile</a>
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.*') ? 'nav-link-active' : '' }}">Admin</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn-secondary">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn-secondary">Login</a>
                        <a href="{{ route('register') }}" class="btn-primary">Register</a>
                    @endauth
                </nav>
            </div>

            <nav class="nav-panel mx-auto hidden max-w-7xl px-4 pb-4 sm:px-6 lg:hidden">
                <div class="panel-muted flex flex-col gap-2 p-3 text-sm">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'nav-link-active' : '' }}">Home</a>
                    <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'nav-link-active' : '' }}">Products</a>
                    @auth
                        <a href="{{ route('cart.index') }}" class="nav-link {{ request()->routeIs('cart.*') ? 'nav-link-active' : '' }}">Cart</a>
                        <a href="{{ route('orders.index') }}" class="nav-link {{ request()->routeIs('orders.*') ? 'nav-link-active' : '' }}">Orders</a>
                        <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'nav-link-active' : '' }}">Profile</a>
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.*') ? 'nav-link-active' : '' }}">Admin</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn-secondary w-full justify-center">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn-secondary w-full justify-center">Login</a>
                        <a href="{{ route('register') }}" class="btn-primary w-full justify-center">Register</a>
                    @endauth
                </div>
            </nav>
        </header>

        @include('partials.flash')

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            @yield('content')
        </main>
    </div>
</body>
</html>
