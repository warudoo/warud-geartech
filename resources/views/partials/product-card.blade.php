<article class="group panel overflow-hidden">
    <div class="relative aspect-[5/4] overflow-hidden rounded-2xl border border-slate-200 bg-slate-100">
        <img src="{{ $product->display_image_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
        <div class="absolute inset-x-0 bottom-0 flex items-center justify-between bg-gradient-to-t from-white via-white/95 to-transparent px-4 py-3 text-xs uppercase tracking-[0.18em] text-slate-600">
            <span>{{ $product->category->name }}</span>
            <span>Stock {{ $product->stock }}</span>
        </div>
    </div>
    <div class="mt-4 space-y-3">
        <div>
            <p class="text-xs uppercase tracking-[0.18em] text-slate-500">{{ $product->brand }} • {{ $product->sku }}</p>
            <h3 class="mt-2 text-xl font-semibold text-slate-900">
                <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
            </h3>
        </div>
        <p class="text-sm leading-6 text-slate-600">{{ str($product->description)->limit(120) }}</p>
        <div class="flex items-center justify-between gap-3">
            <div>
                <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Price</p>
                <p class="font-display text-2xl text-red-600">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</p>
            </div>
            <a href="{{ route('products.show', $product->slug) }}" class="btn-primary">View Detail</a>
        </div>
    </div>
</article>
