@php
    $viewer = auth()->user();
    $isAdminUser = $viewer?->isAdmin() ?? false;
    $isBuyerUser = $viewer?->isBuyer() ?? false;
@endphp

@auth

    {{-- ADMIN --}}
    @if ($isAdminUser)
        <a href="{{ route('admin.dashboard') }}"
            class="nav-link {{ request()->routeIs('admin.dashboard') ? 'nav-link-active' : '' }}">
            <i data-lucide="home" class="w-4 h-4"></i>
            &nbsp;Dashboard
        </a>

        <a href="{{ route('admin.products.index') }}"
            class="nav-link {{ request()->routeIs('admin.products.*') ? 'nav-link-active' : '' }}">
            <i data-lucide="package" class="w-4 h-4"></i>
            &nbsp;Products
        </a>

        <a href="{{ route('admin.categories.index') }}"
            class="nav-link {{ request()->routeIs('admin.categories.*') ? 'nav-link-active' : '' }}">
            <i data-lucide="search" class="w-4 h-4"></i>
            &nbsp;Categories
        </a>

        <a href="{{ route('admin.orders.index') }}"
            class="nav-link {{ request()->routeIs('admin.orders.*') ? 'nav-link-active' : '' }}">
            <i data-lucide="clipboard-list" class="w-4 h-4"></i>
            &nbsp;Orders
        </a>

        <a href="{{ route('admin.reports.index') }}"
            class="nav-link {{ request()->routeIs('admin.reports.*') ? 'nav-link-active' : '' }}">
            <i data-lucide="file-text" class="w-4 h-4"></i>
            &nbsp;Reports
        </a>

        <span
            class="inline-flex items-center rounded-full border border-red-200 bg-red-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-red-700">
            Admin Only
        </span>

        {{-- BUYER --}}
    @elseif($isBuyerUser)
        <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'nav-link-active' : '' }}">
            <i data-lucide="home" class="w-4 h-4"></i>
            &nbsp;Home
        </a>

        <a href="{{ route('products.index') }}"
            class="nav-link {{ request()->routeIs('products.*') ? 'nav-link-active' : '' }}">
            <i data-lucide="package" class="w-4 h-4"></i>
            &nbsp;Products
        </a>

        <a href="{{ route('cart.index') }}" class="nav-link {{ request()->routeIs('cart.*') ? 'nav-link-active' : '' }}">
            <i data-lucide="shopping-cart" class="w-4 h-4"></i>
            &nbsp;Cart
            <span class="ml-2 badge-danger text-xs">
                {{ $viewer->cartItems()->sum('quantity') }}
            </span>
        </a>

        <a href="{{ route('orders.index') }}"
            class="nav-link {{ request()->routeIs('orders.*') ? 'nav-link-active' : '' }}">
            <i data-lucide="clipboard-list" class="w-4 h-4"></i>
            &nbsp;Orders
        </a>

        <a href="{{ route('profile.edit') }}"
            class="nav-link {{ request()->routeIs('profile.*') ? 'nav-link-active' : '' }}">
            <i data-lucide="user" class="w-4 h-4"></i>
            &nbsp;Profile
        </a>
    @endif

    {{-- LOGOUT --}}
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn-secondary">Logout</button>
    </form>
@else
    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'nav-link-active' : '' }}">
        <i data-lucide="home" class="w-4 h-4"></i>
        Home
    </a>

    <a href="{{ route('products.index') }}"
        class="nav-link {{ request()->routeIs('products.*') ? 'nav-link-active' : '' }}">
        <i data-lucide="package" class="w-4 h-4"></i>
        Products
    </a>

    <a href="{{ route('login') }}" class="btn-secondary">Login</a>
    <a href="{{ route('register') }}" class="btn-primary">Register</a>

@endauth
