@extends('layouts.app')

@section('content')
    <section class="mb-8">
        <p class="eyebrow">Cart Staging</p>
        <h1 class="page-title">Your Cart</h1>
    </section>

    <div x-data="cartSelection({
        items: {{ $cartItems->map(
            fn($i) => [
                'id' => (int) $i->id,
                'price' => (int) $i->product->price,
                'qty' => (int) $i->quantity,
            ],
        ) }}
    })" class="grid gap-8 lg:grid-cols-[1.3fr_0.7fr]">

        {{-- LEFT --}}
        <div class="space-y-4">

            @if ($cartItems->isNotEmpty())
                <label class="flex items-center gap-2 panel">
                    <input type="checkbox" @change="selected = $event.target.checked ? items.map(i => i.id) : []">
                    <span class="text-sm text-slate-600">Select All</span>
                </label>
            @endif

            @forelse($cartItems as $item)
                <article class="panel p-4 cursor-pointer hover:bg-slate-50"
                    :class="selected.includes({{ (int) $item->id }}) ? 'ring-2 ring-red-500' : ''"
                    @click="
        if (!$event.target.closest('button, input, form')) {
            let id = {{ (int) $item->id }};
            if (selected.includes(id)) {
                selected = selected.filter(i => i !== id);
            } else {
                selected.push(id);
            }
        }
    ">

                    {{-- GRID WRAPPER --}}
                    <div class="grid gap-4 lg:grid-cols-[1fr_auto]">

                        {{-- LEFT CONTENT --}}
                        <div class="flex gap-4 min-w-0">

                            <input type="checkbox" :value="{{ (int) $item->id }}" x-model.number="selected" @click.stop
                                class="self-center h-5 w-5 shrink-0">

                            <div class="h-24 w-24 shrink-0 overflow-hidden rounded-xl border bg-slate-100">
                                <img src="{{ $item->product->display_image_url }}" class="h-full w-full object-cover">
                            </div>

                            <div class="min-w-0">
                                <p class="eyebrow">{{ $item->product->category->name }}</p>

                                <h2 class="text-lg font-semibold leading-tight line-clamp-2">
                                    {{ $item->product->name }}
                                </h2>

                                <p class="text-sm text-slate-500 truncate">
                                    {{ $item->product->brand }} • {{ $item->product->sku }}
                                </p>

                                <p class="mt-2 text-xl text-red-600 font-display">
                                    Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>

                        {{-- RIGHT ACTION --}}
                        <div class="flex flex-row lg:flex-col gap-3 justify-between lg:justify-start">

                            <form method="POST" action="{{ route('cart.update', $item) }}" class="flex items-center gap-5"
                                @click.stop>
                                @csrf @method('PATCH')

                                <input type="number" name="quantity" value="{{ $item->quantity }}"
                                    class="form-input w-16 text-center sm:ml-2">

                                <button type="submit" class="btn-secondary">
                                    Update
                                </button>
                            </form>

                            <form method="POST" action="{{ route('cart.destroy', $item) }}" class="w-auto lg:w-full"
                                @click.stop>
                                @csrf @method('DELETE')

                                <button type="submit" class="btn-danger w-full">
                                    Remove
                                </button>
                            </form>

                        </div>

                    </div>
                </article>
            @empty
                <div class="panel">Cart kosong</div>
            @endforelse
        </div>

        {{-- RIGHT SUMMARY --}}
        <aside class="panel space-y-5 h-fit">
            <h2 class="section-title">Summary</h2>

            <div>
                <div class="flex justify-between text-sm">
                    <span>Items</span>
                    <span x-text="totalQty"></span>
                </div>

                <div class="flex justify-between mt-2 text-lg font-semibold">
                    <span>Subtotal</span>
                    <span x-text="'Rp ' + subtotal.toLocaleString('id-ID')"></span>
                </div>
            </div>

            <a href="{{ route('products.index') }}" class="btn-secondary w-full">
                Continue Shopping
            </a>

            @if ($cartItems->isNotEmpty())
                <form method="GET" action="{{ route('checkout.show') }}">

                    <template x-for="id in selected" :key="id">
                        <input type="hidden" name="cart_item_ids[]" :value="id">
                    </template>

                    <button type="submit" class="btn-primary w-full" :disabled="selected.length === 0">
                        Proceed To Checkout
                    </button>
                </form>
            @endif
        </aside>
    </div>

    <script>
        function cartSelection(data) {
            return {
                items: data.items,
                selected: [],

                get selectedItems() {
                    return this.items.filter(i => this.selected.includes(i.id))
                },

                get totalQty() {
                    return this.selectedItems.reduce((s, i) => s + i.qty, 0)
                },

                get subtotal() {
                    return this.selectedItems.reduce((s, i) => s + (i.qty * i.price), 0)
                }
            }
        }
    </script>
@endsection
