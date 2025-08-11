<div class="max-w-5xl mx-auto p-8 text-gray-800 bg-gray-100 min-h-screen">
    {{-- HEADER --}}
    <div
        class="flex items-center justify-between bg-gradient-to-tr from-green-200 to-pink-400 rounded-3xl p-8 text-white mb-10 shadow-lg">
        <div>
            <h1 class="text-5xl font-extrabold mb-2 tracking-tight">Arcade Bakery</h1>
            <div class="text-base flex gap-6 items-center mt-2">
                <div class="flex items-center gap-1"><span>📍</span> Awesome City, The Best Country</div>
                <div class="flex items-center gap-1"><span>📶</span> CoolWiFiPassword</div>
            </div>
            <p class="mt-3 text-white/90 text-base">Here you can add any additional information about your QR code menu
            </p>
        </div>
        <img src="{{ asset('images/bakery-desktop.jpg') }}" alt="Bakery"
            class="hidden md:block w-40 h-40 object-contain rounded-full shadow-lg bg-white/20">
    </div>

    {{-- CATEGORY FILTER --}}
    <div class="flex flex-wrap gap-3 justify-start mb-8">
        <button wire:click="$set('selectedCategory', '')"
            class="px-6 py-2 rounded-full border font-semibold text-base transition-all duration-150 {{ $selectedCategory == '' ? 'bg-green-900 text-white shadow' : 'bg-white text-green-900 border-green-900 hover:bg-green-50' }}">
            All
        </button>
        @foreach ($categories as $category)
            <button wire:click="$set('selectedCategory', {{ $category->id }})"
                class="px-6 py-2 rounded-full border font-semibold text-base transition-all duration-150 {{ $selectedCategory == $category->id ? 'bg-green-900 text-white shadow' : 'bg-white text-green-900 border-green-900 hover:bg-green-50' }}">
                {{ $category->name }}
            </button>
        @endforeach
    </div>

    {{-- MENUS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach ($menus as $menu)
            <div class="bg-white rounded-2xl shadow-md overflow-hidden border flex flex-col">
                <img src="{{ $menu->image ? asset('storage/' . $menu->image) : asset('images/default-menu.png') }}"
                    alt="{{ $menu->name }}" class="w-full object-cover aspect-square">

                <div class="p-6 flex flex-col flex-1">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-xl font-bold break-words max-w-[180px]">{{ $menu->name }}</h3>
                        <span class="text-sm text-gray-400">{{ $menu->weight ?? '150g' }}</span>
                    </div>
                    <p class="text-base text-gray-600 mb-3 flex-1">{{ $menu->description }}</p>
                    <div class="font-bold text-green-900 text-xl mb-4">
                        Rp {{ number_format($menu->price, 2) }}
                    </div>

                    {{-- Quantity selector + Add to Cart --}}
                    <div class="flex items-center justify-between mt-auto">
                        <div class="flex items-center space-x-2 bg-gray-100 px-3 py-1 rounded-full">
                            <button type="button" wire:click="decreaseQty({{ $menu->id }})"
                                class="text-gray-600 hover:text-white hover:bg-green-900 rounded-full p-1 w-7 h-7 flex items-center justify-center">&minus;</button>
                            <span class="w-6 text-center">{{ $quantities[$menu->id] ?? 1 }}</span>
                            <button type="button" wire:click="increaseQty({{ $menu->id }})"
                                class="text-gray-600 hover:text-white hover:bg-green-900 rounded-full p-1 w-7 h-7 flex items-center justify-center">&#43;</button>
                        </div>
                        <button wire:click="addToCart({{ $menu->id }})"
                            class="ml-4 bg-green-900 hover:bg-green-900 text-white text-sm px-4 py-2 rounded-full shadow">Add</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if (!empty($cart))
        {{-- HIDE FLOATING CART WHEN CART SUMMARY IS OPEN --}}
        <div x-data="{ showCart: false }">
            <div x-show="!showCart" class="fixed bottom-4 inset-x-4 z-50">
                <div class="bg-green-900 text-white rounded-2xl shadow-xl px-4 py-3 flex items-center justify-between">
                    <div>
                        <p class="text-lg font-semibold">Cart</p>
                        <p class="text-sm text-gray-200">{{ collect($cart)->sum('quantity') }} items</p>
                    </div>
                    <div class="flex -space-x-2">
                        @foreach (array_slice($cart, 0, 3) as $item)
                            <img src="{{ asset('storage/' . $item['image']) }}"
                                class="w-10 h-10 rounded-full border-2 border-white object-cover"
                                alt="{{ $item['name'] }}">
                        @endforeach
                    </div>
                </div>

                <div class="fixed bottom-6 right-6">
                    <button @click="showCart = !showCart"
                        class="bg-green-900 text-white px-4 py-3 rounded-full shadow-lg flex items-center gap-2">
                        <span class="font-semibold">Cart</span>
                        <span class="text-sm">({{ collect($cart)->sum('quantity') }} items)</span>
                        @foreach (array_unique(array_column($cart ?? [], 'image')) as $i => $img)
                            <img src="{{ asset('storage/' . $img) }}"
                                class="w-8 h-8 rounded-full border-2 border-white {{ $i > 0 ? '-ml-4' : '' }} shadow"
                                style="z-index:{{ 10 - $i }};">
                        @endforeach
                    </button>
                </div>
            </div>

            <!-- CART DETAIL PANEL -->
            <div x-show="showCart"
                class="fixed bottom-0 left-0 right-0 bg-white rounded-t-3xl shadow-xl p-6 z-40 max-h-[60vh] overflow-y-auto"
                x-transition>
                <h3 class="text-xl font-bold mb-4">Cart Summary</h3>
                @foreach ($cart as $item)
                    <div
                        class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 space-y-3 sm:space-y-0">
                        <div class="flex items-start gap-3 flex-grow">
                            <img src="{{ asset('storage/' . $item['image']) }}"
                                class="w-12 h-12 rounded-full object-cover">
                            <div class="min-w-0">
                                <div class="font-medium break-words max-w-[180px] sm:max-w-none leading-snug">
                                    {{ $item['name'] }}</div>
                                <div class="text-sm text-gray-500">Rp {{ number_format($item['price'], 2) }}</div>
                            </div>
                        </div>
                        <div
                            class="flex justify-between items-center sm:items-center sm:justify-end sm:gap-4 w-full sm:w-auto">
                            <div class="flex items-center gap-2">
                                <button wire:click="decreaseQty({{ $item['id'] }})"
                                    class="w-8 h-8 flex justify-center items-center bg-green-900 rounded-full text-lg text-white">&minus;</button>
                                <span>{{ $item['quantity'] }}</span>
                                <button wire:click="increaseQty({{ $item['id'] }})"
                                    class="w-8 h-8 flex justify-center items-center bg-green-900 rounded-full text-lg text-white">&#43;</button>
                            </div>
                            <div class="text-right font-semibold w-24">Rp
                                {{ number_format($item['price'] * $item['quantity'], 2) }}</div>
                        </div>
                    </div>
                @endforeach
                <div class="p-4 pb-10 bg-gray-50 rounded-lg">
                    <hr class="my-4">
                    <div class="flex justify-between font-bold text-lg mb-4">
                        <span>Total</span>
                        <span>Rp
                            {{ number_format(collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']), 2) }}</span>
                    </div>
                    {{-- <button wire:click="checkout"
                        class="w-full bg-orange-500 text-white py-3 rounded-xl text-lg font-semibold hover:bg-orange-600 transition">Checkout</button> --}}
                    <div class="mt-4">
                        <!-- Replace Checkout button with this -->
                        <div x-data="{ paymentStep: 'cart' }">
                            <!-- Step 1: Cart Checkout Button -->
                            <div x-show="paymentStep === 'cart'">
                                <button @click="paymentStep = 'payment'"
                                    class="w-full bg-green-900 text-white py-3 rounded-xl text-lg font-semibold hover:bg-orange-600 transition">
                                    Checkout
                                </button>
                            </div>

                            <!-- Step 2: Choose Payment Method -->
                            <div x-show="paymentStep === 'payment'" x-transition>
                                <h4 class="text-lg font-bold mb-4">Choose Payment Method</h4>
                                <div class="space-y-3">
                                    <button @click="alert('Cash selected')"
                                        class="w-full bg-green-600 text-white py-3 rounded-xl font-semibold hover:bg-green-700 transition">
                                        💵 Cash
                                    </button>
                                    <button @click="alert('Credit Card selected')"
                                        class="w-full bg-blue-600 text-white py-3 rounded-xl font-semibold hover:bg-blue-700 transition">
                                        💳 Credit Card
                                    </button>
                                    <button @click="alert('QR Payment selected')"
                                        class="w-full bg-purple-600 text-white py-3 rounded-xl font-semibold hover:bg-purple-700 transition">
                                        📱 QR Code
                                    </button>
                                </div>
                                <div class="flex justify-between mt-4">
                                    {{-- <div class="flex justify-center gap-8 mt-4"> --}}
                                    <button @click="paymentStep = 'cart'"
                                        class="text-sm text-gray-900 hover:text-green-900">
                                        Back to Cart
                                    </button>
                                    <button @click="showCart = false" class="text-sm text-gray-900 hover:text-red-700">
                                        Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- @if (paymentStep === 'payment')
                        <button @click="showCart = true"
                            class="mt-4 w-full text-sm text-gray-500 hover:text-gray-700">Close</button>
                    @endif --}}
                </div>
            </div>
        </div>
    @endif

    @if ($menus->isEmpty())
        <p class="text-center text-gray-500 mt-16 text-lg">No menu items found.</p>
    @endif
</div>
