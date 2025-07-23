<div class="max-w-5xl mx-auto p-8 text-gray-800 bg-gray-100 min-h-screen">

    {{-- HEADER --}}
    <div class="flex items-center justify-between bg-gradient-to-tr from-orange-200 to-pink-400 rounded-3xl p-8 text-white mb-10 shadow-lg">
        <div>
            <h1 class="text-5xl font-extrabold mb-2 tracking-tight">Arcade Bakery</h1>
            <div class="text-base flex gap-6 items-center mt-2">
                <div class="flex items-center gap-1"><span>📍</span> Awesome City, The Best Country</div>
                <div class="flex items-center gap-1"><span>📶</span> CoolWiFiPassword</div>
            </div>
            <p class="mt-3 text-white/90 text-base">Here you can add any additional information about your QR code menu</p>
        </div>
        <img src="{{ asset('images/bakery-desktop.png') }}" alt="Bakery" class="hidden md:block w-40 h-40 object-contain rounded-full shadow-lg bg-white/20">
    </div>

    {{-- CATEGORY FILTER --}}
    <div class="flex flex-wrap gap-3 justify-start mb-8">
        <button wire:click="$set('selectedCategory', '')"
            class="px-6 py-2 rounded-full border font-semibold text-base transition-all duration-150 {{ $selectedCategory == '' ? 'bg-orange-500 text-white shadow' : 'bg-white text-orange-500 border-orange-500 hover:bg-orange-50' }}">
            All
        </button>
        @foreach($categories as $category)
            <button wire:click="$set('selectedCategory', {{ $category->id }})"
                class="px-6 py-2 rounded-full border font-semibold text-base transition-all duration-150 {{ $selectedCategory == $category->id ? 'bg-orange-500 text-white shadow' : 'bg-white text-orange-500 border-orange-500 hover:bg-orange-50' }}">
                {{ $category->name }}
            </button>
        @endforeach
    </div>

    {{-- MENUS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($menus as $menu)
            <div class="bg-white rounded-2xl shadow-md overflow-hidden border flex flex-col">
                @if($menu->image)
                    <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}"
                        class="w-full object-cover aspect-video">
                @else
                    <img src="{{ asset('images/default-menu.png') }}" alt="No Image"
                        class="w-full object-cover aspect-video">
                @endif

                <div class="p-6 flex flex-col flex-1">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-xl font-bold">{{ $menu->name }}</h3>
                        <span class="text-sm text-gray-400">{{ $menu->weight ?? '150g' }}</span>
                    </div>
                    <p class="text-base text-gray-600 mb-3 flex-1">{{ $menu->description }}</p>
                    <div class="font-bold text-orange-600 text-xl mb-4">
                        ${{ number_format($menu->price, 2) }}
                    </div>

                    {{-- Quantity & Add to Cart --}}
                    <div class="flex items-center gap-3 mt-auto">
                        <input type="number"
                            min="1"
                            wire:model.defer="quantities.{{ $menu->id }}"
                            class="w-20 border rounded px-3 py-2 text-base focus:ring-2 focus:ring-orange-300"
                            placeholder="Qty">
                        <button wire:click="addToCart({{ $menu->id }})"
                            class="bg-orange-500 hover:bg-orange-600 text-white text-base px-5 py-2 rounded transition-all duration-150 shadow">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($menus->isEmpty())
        <p class="text-center text-gray-500 mt-16 text-lg">No menu items found.</p>
    @endif
</div>
