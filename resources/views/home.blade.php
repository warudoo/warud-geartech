@extends('layouts.app')

@section('content')
    <section class="hero-grid mb-6 md:mb-10">
        <div x-data="heroCarousel()" x-init="start()"
            class="panel relative w-full md:w-[90%] lg:w-[78%] mx-auto overflow-hidden bg-transparent">

            {{-- Slides --}}
            <div class="relative w-full aspect-[4/3] sm:aspect-[16/9] md:aspect-[16/7]">
                <template x-for="(slide, index) in slides" :key="index">
                    <div x-show="active === index" x-transition:enter="transition ease-out duration-700"
                        x-transition:enter-start="opacity-0 scale-105" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-500"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        class="absolute inset-0">

                        <img :src="slide" class="w-full h-full object-contain bg-black sm:object-cover"
                            alt="Hero Image">
                    </div>
                </template>
            </div>

            {{-- Overlay (aktif di semua device, tapi subtle di mobile) --}}
            <div class="absolute inset-0 bg-gradient-to-r from-black/30 via-black/10 to-transparent"></div>

            {{-- Indicator --}}
            <div class="absolute bottom-3 md:bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
                <template x-for="(slide, index) in slides" :key="index">
                    <button @click="active = index"
                        :class="active === index ? 'bg-white w-5 md:w-6' : 'bg-white/50 w-2 md:w-3'"
                        class="h-2 md:h-3 rounded-full transition-all duration-300">
                    </button>
                </template>
            </div>

        </div>
    </section>



    <section class="mb-10">
        <div class="mb-6 flex items-end justify-between gap-4">
            <div>
                <p class="eyebrow">Featured Products</p>
                <h2 class="section-title">Featured Picks</h2>
            </div>
            <a href="{{ route('products.index') }}" class="btn-secondary">See Full Catalog</a>
        </div>

        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @forelse($featuredProducts as $product)
                @include('partials.product-card', ['product' => $product])
            @empty
                <div class="panel text-slate-600">No featured products yet.</div>
            @endforelse
        </div>
    </section>

    <section class="space-y-6">
        <div class="panel">
            <p class="eyebrow">Category Map</p>
            <h2 class="mb-4 section-title">Shop By Category</h2>
            <div class="flex gap-4 overflow-x-auto pb-2 snap-x snap-mandatory">
                @foreach ($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                        class="panel-muted shrink-0 w-64 snap-start p-4 transition hover:border-red-500/40 hover:bg-red-500/5">
                        <div class="flex flex-col gap-2 h-full">
                            <p class="text-base font-semibold text-slate-900">
                                {{ $category->name }}
                            </p>
                            <div
                                class="h-20 overflow-y-auto overflow-x-hidden text-sm text-left leading-relaxed text-slate-600 pr-1 break-words no-scrollbar">
                                {{ $category->description }}
                            </div>
                            <div class="mt-auto">
                                <span
                                    class="inline-block rounded-full bg-red-50 px-3 py-1 text-sm font-semibold uppercase tracking-[0.18em] text-red-700">
                                    {{ $category->products_count }} Products
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="panel">
            <div class="mb-6">
                <p class="eyebrow">Latest Additions</p>
                <h2 class="section-title">Latest Inventory</h2>
            </div>
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($latestProducts as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </div>
    </section>
@endsection

<script>
    function heroCarousel() {
        return {
            active: 0,
            interval: null,
            slides: [
                '{{ asset('images/hero1.png') }}',
                '{{ asset('images/hero2.png') }}'
            ],

            start() {
                this.interval = setInterval(() => {
                    this.next()
                }, 7000)
            },

            next() {
                this.active = (this.active + 1) % this.slides.length
            }
        }
    }
</script>
