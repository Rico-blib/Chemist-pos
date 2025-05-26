<div>{{-- Layout container --}}
<div class="p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
        <h2 class="text-2xl font-semibold text-zinc-900 dark:text-white">Medicines</h2>
        <div class="flex flex-col sm:flex-row gap-4">
            <input type="text" wire:model.debounce.300ms="search" placeholder="Search by name or manufacturer"
                class="px-4 py-2 rounded-lg border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">

            <select wire:model="filterCategory"
                class="px-4 py-2 rounded-lg border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">
                <option value="">All Categories</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Flash Message --}}
    @if (session()->has('success'))
        <div
            class="p-4 text-sm text-green-700 bg-green-100 border border-green-300 rounded-lg dark:bg-green-800 dark:text-green-200 dark:border-green-600">
            {{ session('success') }}
        </div>
    @endif

    {{-- Add Button --}}
    <div class="flex justify-end">
        <button wire:click="create"
            class="px-4 py-2 rounded-lg font-semibold bg-blue-600 text-white hover:bg-blue-700 transition">
            Add Medicine
        </button>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left text-zinc-600 dark:text-zinc-300">
            <thead class="bg-zinc-100 dark:bg-zinc-800">
                <tr>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Category</th>
                    <th class="px-4 py-2">Manufacturer</th>
                    <th class="px-4 py-2">Quantity</th>
                    <th class="px-4 py-2">Price</th>
                    <th class="px-4 py-2">Expiry</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($medicines as $medicine)
                    <tr class="border-b border-zinc-200 dark:border-zinc-700">
                        <td class="px-4 py-2">{{ $medicine->name }}</td>
                        <td class="px-4 py-2">{{ $medicine->category }}</td>
                        <td class="px-4 py-2">{{ $medicine->manufacturer }}</td>
                        <td class="px-4 py-2">{{ $medicine->quantity }}</td>
                        <td class="px-4 py-2">{{ $medicine->price }}</td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($medicine->expiry_date)->format('d M Y') }}</td>
                        <td class="px-4 py-2 space-x-2">
                            <button wire:click="edit({{ $medicine->id }})" @click="showModal = true"
                                class="text-blue-600 hover:underline">Edit</button>
                            <button wire:click="delete({{ $medicine->id }})" onclick="return confirm('Are you sure?')"
                                class="text-red-600 hover:underline">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-2 text-center text-zinc-500 dark:text-zinc-400">
                            No medicines found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $medicines->links() }}
    </div>

</div> {{-- END of layout container --}}

{{-- MODAL INSIDE CONTENT AREA, NOT FULL SCREEN --}}
@if ($showModal)
    <div class="relative z-10">
        {{-- Overlay only inside content --}}
        <div class="fixed inset-0 bg-black bg-opacity-40 backdrop-blur-sm"></div>

        {{-- Centered Modal inside page content --}}
        <div class="absolute inset-0 flex items-start justify-center p-4">
            <div class="w-full max-w-xl bg-white dark:bg-zinc-900 rounded-2xl shadow-xl border border-zinc-300 dark:border-zinc-700 mt-20">

                {{-- Modal Header --}}
                <div class="flex justify-between items-center p-4 border-b border-zinc-200 dark:border-zinc-700">
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">
                        {{ $editingMedicine ? 'Edit Medicine' : 'Add Medicine' }}
                    </h3>
                    <button wire:click="$set('showModal', false)"
                        class="text-zinc-500 hover:text-red-500 text-2xl leading-none" aria-label="Close modal">
                        &times;
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="p-4 max-h-[70vh] overflow-y-auto">
                    @livewire('medicines.form', ['medicine' => $editingMedicine], key($editingMedicine?->id ?? 'new'))
                </div>

            </div>
        </div>
    </div>
@endif
