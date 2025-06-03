<div>
    <h1 class="text-xl font-semibold mb-4">Sales POS</h1>

    @if (session()->has('success'))
        <div class="mb-4 text-green-700 bg-green-100 p-2 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 text-red-700 bg-red-100 p-2 rounded">
            {{ session('error') }}
        </div>
    @endif

    {{-- Search Bar --}}
    <div class="mb-4 flex gap-2">
        <input type="text" wire:model.lazy="search" placeholder="Search medicine by name..."
            class="w-full px-4 py-2 border rounded shadow" />

        <button wire:click="$refresh" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Search
        </button>
    </div>


    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Medicine List --}}
        <div class="col-span-2 space-y-2">
            <h2 class="text-lg font-semibold mb-2">Available Medicines</h2>

            @if (empty($medicines))
                <p class="text-sm text-gray-500">No medicines found.</p>
            @else
                @foreach ($medicines as $medicine)
                    <div class="p-3 border rounded flex justify-between items-center shadow-sm">
                        <div>
                            <p class="font-medium">{{ $medicine['name'] }}</p>
                            <p class="text-sm text-gray-500">Code: {{ $medicine['code'] ?? 'N/A' }}</p>
                        </div>
                        <button wire:click="addToCart({{ $medicine['id'] }})"
                            class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                            Add to Cart
                        </button>
                    </div>
                @endforeach
            @endif
        </div>

        {{-- Cart --}}
        <div class="col-span-1 space-y-2">
            <h2 class="text-lg font-semibold mb-2">Cart</h2>

            @if (empty($cart))
                <p class="text-sm text-gray-500">Cart is empty.</p>
            @else
                @foreach ($cart as $item)
                    <div class="p-3 border rounded shadow-sm">
                        <div class="flex justify-between items-center">
                            <p class="font-semibold">{{ $item['name'] }}</p>
                            <button wire:click="removeFromCart({{ $item['id'] }})"
                                class="text-red-500 hover:underline text-sm">Remove</button>
                        </div>
                        <div class="flex items-center gap-2 mt-2">
                            <button wire:click="decrementQuantity({{ $item['id'] }})"
                                class="px-2 py-1 bg-gray-200 rounded">−</button>
                            <span>{{ $item['quantity'] }}</span>
                            <button wire:click="incrementQuantity({{ $item['id'] }})"
                                class="px-2 py-1 bg-gray-200 rounded">+</button>
                            <span class="ml-auto text-sm text-gray-700">
                                @ Ksh {{ number_format($item['price'], 2) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            @endif

            {{-- Totals --}}
            <div class="mt-4 p-4 border rounded shadow">
                <p>Subtotal: <strong>Ksh {{ number_format($subtotal, 2) }}</strong></p>
                <p>Tax (16%): <strong>Ksh {{ number_format($tax, 2) }}</strong></p>
                <p class="text-lg font-bold">Total: Ksh {{ number_format($total, 2) }}</p>

                <button wire:click="checkout" class="mt-4 w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                    Checkout
                </button>
            </div>
        </div>
    </div>
</div>
