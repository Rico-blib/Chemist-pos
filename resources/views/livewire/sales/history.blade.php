<div class="flex flex-col gap-4 p-4">
    <div class="flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-sm font-medium mb-1">Search</label>
            <input type="text" wire:model.defer="search" class="w-full px-3 py-2 border rounded-md"
                placeholder="Medicine name or ID">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">From Date</label>
            <input type="date" wire:model.defer="from_date" class="w-full px-3 py-2 border rounded-md">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">To Date</label>
            <input type="date" wire:model.defer="to_date" class="w-full px-3 py-2 border rounded-md">
        </div>

        <div class="flex flex-col gap-2 mt-5">
            <button wire:click="filterSales" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Search
            </button>
            <button wire:click="resetFilters" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                Clear
            </button>
        </div>

        {{-- ✅ Export Buttons --}}
        <div class="flex flex-col gap-2 mt-5 w-full sm:w-auto">
            <a href="{{ route('sales.export.pdf', ['from_date' => $from_date, 'to_date' => $to_date, 'search' => $search]) }}"
                target="_blank" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-center shadow">
                Export PDF
            </a>
            <a href="{{ route('sales.export.excel', ['from_date' => $from_date, 'to_date' => $to_date, 'search' => $search]) }}"
                target="_blank" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-center shadow">
                Export Excel
            </a>
        </div>

    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full border mt-4 text-sm">
            <thead class="bg-gray-100 dark:bg-zinc-800">
                <tr>
                    <th class="p-2 text-left">Sale ID</th>
                    <th class="p-2 text-left">Date</th>
                    <th class="p-2 text-left">Items</th>
                    <th class="p-2 text-left">Total</th>
                    <th class="p-2 text-left">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sales as $sale)
                    <tr class="border-t dark:border-zinc-700">
                        <td class="p-2">{{ $sale->id }}</td>
                        <td class="p-2">{{ $sale->created_at->format('Y-m-d H:i') }}</td>
                        <td class="p-2">
                            <ul>
                                @foreach ($sale->items as $item)
                                    <li>{{ $item->medicine->name }} x {{ $item->quantity }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="p-2">₦{{ number_format($sale->total, 2) }}</td>
                        <td class="p-2">
                            <a href="{{ route('sales.receipt.print', $sale->id) }}" target="_blank"
                                class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Print Receipt
                            </a>
                            <a href="{{ route('sales.receipt.download', $sale->id) }}"
                                class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">
                                Download PDF
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center">No sales found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $sales->links() }}
        </div>
    </div>
</div>
