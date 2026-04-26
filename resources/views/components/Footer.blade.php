<footer class="bg-red-800 text-white">
    <div class="max-w-7xl mx-auto px-6 py-10 grid gap-8 md:grid-cols-3">

        {{-- Brand --}}
        <div>
            <div class="flex items-center gap-2 mb-4">
                <div class="flex h-10 w-10 p-6 items-center justify-center rounded-xl border border-red-200 bg-red-50">
                    <span class="font-bold text-red-700">WGT</span>
                </div>
                <div>
                    <h2 class="font-semibold text-lg">Warud Geartech</h2>
                    <p class="text-sm text-white/70">Solution</p>
                </div>
            </div>

            <p class="text-sm text-white/80 leading-relaxed">
                Warud Geartech Solution delivers high performance gaming gear served to elevate every moment of your
                play.
            </p>
        </div>

        {{-- Kategori (Dropdown Mobile) --}}
        <div x-data="{ open: false }">
            <button @click="open = !open"
                class="w-full flex items-center justify-between font-semibold mb-2 md:mb-4 md:cursor-default">
                <span>Kategori</span>

                {{-- icon arrow (mobile only) --}}
                <svg class="w-4 h-4 transition-transform md:hidden" :class="{ 'rotate-180': open }" fill="none"
                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            {{-- List --}}
            <ul class="space-y-2 text-sm text-white/80 overflow-hidden transition-all duration-300"
                :class="open ? 'max-h-96 mt-2' : 'max-h-0 md:max-h-none'">
                @foreach ($categories as $category)
                    <li>
                        <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                            class="hover:text-white block py-1">
                            {{ $category->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Alamat + Media --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <h3 class="font-semibold mb-4">Alamat</h3>
                <ul class="space-y-3 text-sm text-white/80">
                    <li>
                        <a href="https://maps.app.goo.gl/D2oM6DtvGgaKAha49" class="hover:text-white">
                            Jl. Margonda Raya No.88, Kemiri Muka, Beji, Depok, Jawa Barat 16423
                        </a>
                    </li>
                    <li>
                        <a href="https://maps.app.goo.gl/NH9UTPYMyjE8Usp37" class="hover:text-white">
                            Jl. KH. Hasyim Ashari No.12A, Cipondoh, Tangerang, Banten 15148
                        </a>
                    </li>
                </ul>
            </div>

            <div>
                <h3 class="font-semibold mb-4">Media</h3>
                <div class="flex items-center gap-4">
                    <a href="https://www.instagram.com">
                        <x-simpleicon-instagram class="w-6 h-6 text-[#FF0069]" />
                    </a>
                    <a href="https://www.x.com">
                        <x-simpleicon-x class="w-6 h-6 text-black" />
                    </a>
                    <a href="https://www.tiktok.com">
                        <x-simpleicon-tiktok class="w-6 h-6 text-black" />
                    </a>
                    <a href="https://www.whatsapp.com">
                        <x-simpleicon-whatsapp class="w-6 h-6 text-[#25D366]" />
                    </a>
                </div>
            </div>
        </div>

    </div>

    <div class="border-t border-white/20">
        <div class="max-w-7xl mx-auto px-6 py-4 text-center text-sm text-white/70">
            © {{ date('Y') }} Warud Geartech Solution. All rights reserved.
        </div>
    </div>
</footer>
