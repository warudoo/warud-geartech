@php
    $viewer = auth()->user();
    $isAdminUser = $viewer?->isAdmin() ?? false;
    $isBuyerUser = $viewer?->isBuyer() ?? false;
@endphp

@auth

    {{-- ADMIN --}}
    @if ($isAdminUser)
        <a href="{{ route('admin.dashboard') }}" class="nav-link w-full">
            <i data-lucide="home" class="w-4 h-4"></i>
            &nbsp;Dashboard
        </a>

        <a href="{{ route('admin.products.index') }}" class="nav-link w-full">
            <i data-lucide="package" class="w-4 h-4"></i>
            &nbsp;Products
        </a>

        <a href="{{ route('admin.categories.index') }}" class="nav-link w-full">
            <i data-lucide="search" class="w-4 h-4"></i>
            &nbsp;Categories
        </a>

        <a href="{{ route('admin.orders.index') }}" class="nav-link w-full">
            <i data-lucide="clipboard-list" class="w-4 h-4"></i>
            &nbsp;Orders
        </a>

        <a href="{{ route('admin.reports.index') }}" class="nav-link w-full">
            <i data-lucide="file-text" class="w-4 h-4"></i>
            &nbsp;Reports
        </a>

        {{-- BUYER --}}
    @elseif($isBuyerUser)
        <a href="{{ route('home') }}" class="nav-link w-full">
            <i data-lucide="home" class="w-4 h-4"></i>
            &nbsp;Home
        </a>

        <a href="{{ route('products.index') }}" class="nav-link w-full">
            <i data-lucide="package" class="w-4 h-4"></i>
            &nbsp;Products
        </a>

        <a href="{{ route('cart.index') }}" class="nav-link w-full flex justify-between">
            <span class="flex items-center">
                <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                &nbsp;Cart
            </span>
            <span class="badge-danger text-xs">
                {{ $viewer->cartItems()->sum('quantity') }}
            </span>
        </a>

        <a href="{{ route('orders.index') }}" class="nav-link w-full">
            <i data-lucide="clipboard-list" class="w-4 h-4"></i>
            &nbsp;Orders
        </a>

        <a href="{{ route('profile.edit') }}" class="nav-link w-full">
            <i data-lucide="user" class="w-4 h-4"></i>
            &nbsp;Profile
        </a>
    @endif

    {{-- LOGOUT --}}
    <form method="POST" action="{{ route('logout') }}" class="w-full">
        @csrf
        <button type="submit" class="btn-secondary w-full justify-center">
            Logout
        </button>
    </form>
@else
    <a href="{{ route('home') }}" class="nav-link w-full">
        <i data-lucide="home" class="w-4 h-4"></i>
        &nbsp;Home
    </a>

    <a href="{{ route('products.index') }}" class="nav-link w-full">
        <i data-lucide="package" class="w-4 h-4"></i>
        &nbsp;Products
    </a>

    <a href="{{ route('login') }}" class="btn-secondary w-full justify-center">
        Login
    </a>

    <a href="{{ route('register') }}" class="btn-primary w-full justify-center">
        Register
    </a>

@endauth
