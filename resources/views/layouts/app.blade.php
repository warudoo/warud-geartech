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

<body class="min-h-screen text-slate-800 no-scrollbar">
    <div class="min-h-screen bg-grid" x-data="{ open: false }">

        {{-- HEADER --}}
        <header class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/90 backdrop-blur">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

                {{-- TOP BAR --}}
                <div class="flex items-center justify-between py-4">

                    {{-- BRAND --}}
                    <a href="{{ $brandHome }}" class="flex items-center gap-3">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl border border-red-200 bg-red-50">
                            <span class="font-bold text-red-700">GT</span>
                        </div>
                        <div class="hidden sm:block">
                            <p class="font-semibold text-slate-900">Warud Geartech</p>
                            <p class="text-xs text-slate-500">
                                {{ $isAdminUser ? 'Admin Operations' : 'Gaming Peripheral Store' }}
                            </p>
                        </div>
                    </a>

                    {{-- DESKTOP NAV --}}
                    <nav class="hidden lg:flex items-center gap-3 text-sm font-medium">
                        @include('partials.nav-desktop')
                    </nav>

                    {{-- MOBILE TOGGLE --}}
                    <button @click="open = !open"
                        class="lg:hidden inline-flex items-center rounded-lg border px-3 py-2 text-sm">
                        Menu
                    </button>

                </div>

                {{-- MOBILE NAV --}}
                <div x-show="open" x-transition @click.outside="open = false" class="lg:hidden pb-4">
                    <div class="flex flex-col gap-2 rounded-xl border bg-white p-3 shadow-sm">
                        @include('partials.nav-mobile')
                    </div>
                </div>

            </div>
        </header>

        {{-- FLASH --}}
        @include('partials.flash')

        {{-- MAIN --}}
        <main
            class="{{ $isAdminArea
                ? 'mx-auto w-full max-w-[1440px] px-4 py-6 sm:px-6 lg:px-8'
                : 'mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8' }}">
            @yield('content')
        </main>

    </div>
</body>

</html>
