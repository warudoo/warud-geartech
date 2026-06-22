<article class="group panel overflow-hidden flex flex-col">

    {{-- IMAGE --}}
    <div class="relative aspect-[5/4] overflow-hidden rounded-2xl border border-slate-200 bg-slate-100">
        <img src="{{ $product->display_image_url }}" alt="{{ $product->name }}"
            class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
        <div
            class="absolute inset-x-0 bottom-0 flex items-center justify-between bg-gradient-to-t from-white via-white/95 to-transparent px-4 py-3 text-xs uppercase tracking-[0.18em] text-slate-600">
            <span class="font-semibold">{{ $product->category->name }}</span>
            <span class="font-semibold">Stock {{ $product->stock }}</span>
        </div>
    </div>

    {{-- CONTENT --}}
    <div class="mt-4 flex flex-col gap-3 flex-1">

        {{-- TITLE --}}
        <div>
            <p class="text-xs uppercase tracking-[0.18em] text-slate-500">
                {{ $product->brand }} • {{ $product->sku }}
            </p>

            <h3 class="mt-2 text-lg sm:text-xl font-semibold text-slate-900 line-clamp-2">
                <a href="{{ route('products.show', $product->slug) }}">
                    {{ $product->name }}
                </a>
            </h3>
        </div>

        {{-- DESCRIPTION --}}
        <div class="h-24 overflow-y-auto pr-2 text-sm leading-6 text-slate-600 no-scrollbar">
            {!! nl2br(e($product->description)) !!}
        </div>

        {{-- PRICE + CTA --}}
        <div class="mt-auto grid grid-cols-1 sm:grid-cols-[1fr_auto] gap-3 items-end">

            {{-- PRICE --}}
            <div>
                <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Price</p>

                <p class="font-display text-xl sm:text-2xl text-red-600 leading-tight break-words">
                    Rp {{ number_format((float) $product->price, 0, ',', '.') }}
                </p>
            </div>

            {{-- BUTTON --}}
            <a href="{{ route('products.show', $product->slug) }}"
                class="btn-primary w-full sm:w-auto text-center h-10 flex items-center justify-center">
                View Detail
            </a>

        </div>

    </div>
</article>
