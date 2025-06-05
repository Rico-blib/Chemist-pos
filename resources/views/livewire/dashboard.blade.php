<div class="space-y-6">
    {{-- Dashboard KPIs --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="rounded-2xl border border-zinc-200 bg-white p-4 shadow dark:border-zinc-700 dark:bg-zinc-800">
            <h3 class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Today's Sales</h3>
            <p class="text-xl font-semibold text-zinc-800 dark:text-zinc-300">Ksh {{ number_format($totalSalesToday, 2) }}</p>
        </div>
        <div class="rounded-2xl border border-zinc-200 bg-white p-4 shadow dark:border-zinc-700 dark:bg-zinc-800">
            <h3 class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Monthly Revenue</h3>
            <p class="text-xl font-semibold text-zinc-800 dark:text-zinc-300">Ksh {{ number_format($monthlyRevenue, 2) }}</p>
        </div>
        <div class="rounded-2xl border border-zinc-200 bg-white p-4 shadow dark:border-zinc-700 dark:bg-zinc-800">
            <h3 class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Transactions Today</h3>
            <p class="text-xl font-semibold text-zinc-800 dark:text-zinc-300">{{ $transactionsToday }}</p>
        </div>
        <div class="rounded-2xl border border-zinc-200 bg-white p-4 shadow dark:border-zinc-700 dark:bg-zinc-800">
            <h3 class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Top-Selling Medicine</h3>
            <p class="text-xl font-semibold text-zinc-800 dark:text-zinc-300">{{ $topSellingMedicine?->medicine->name ?? 'N/A' }}</p>
        </div>
    </div>

    {{-- Inventory Alerts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="rounded-2xl border border-yellow-300 bg-yellow-50 p-4 shadow dark:border-yellow-600 dark:bg-yellow-900">
            <h2 class="text-lg font-semibold text-yellow-800 dark:text-yellow-200 mb-3">Low Stock Medicines</h2>
            <ul class="list-disc pl-5 text-yellow-800 dark:text-yellow-200">
                @forelse ($lowStockMedicines as $med)
                    <li>{{ $med->name }} ({{ $med->quantity }} left)</li>
                @empty
                    <li>All stocks are sufficient</li>
                @endforelse
            </ul>
        </div>

        <div class="rounded-2xl border border-zinc-200 bg-white p-4 shadow dark:border-zinc-700 dark:bg-zinc-800">
            <h2 class="text-lg font-semibold mb-3 text-zinc-700 dark:text-zinc-100">Medicines Nearing Expiry</h2>
            <ul class="list-disc pl-5 text-zinc-700 dark:text-zinc-200">
                @forelse ($expiringMedicines as $med)
                    <li>{{ $med->name }} (Expires: {{ \Carbon\Carbon::parse($med->expiry_date)->format('d M Y') }})</li>
                @empty
                    <li>No medicines nearing expiry</li>
                @endforelse
            </ul>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="rounded-2xl border border-zinc-200 bg-white p-4 shadow dark:border-zinc-700 dark:bg-zinc-800 overflow-x-auto">
            <h2 class="text-lg font-semibold mb-3 text-zinc-700 dark:text-zinc-100">Recent Sales</h2>
            <table class="w-full text-sm text-zinc-700 dark:text-zinc-200">
                <thead>
                    <tr>
                        <th class="text-left">#</th>
                        <th class="text-left">Total</th>
                        <th class="text-left">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentSales as $sale)
                        <tr>
                            <td>{{ $sale->id }}</td>
                            <td>Ksh {{ number_format($sale->total, 2) }}</td>
                            <td>{{ \Carbon\Carbon::parse($sale->created_at)->format('d M Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="rounded-2xl border border-zinc-200 bg-white p-4 shadow dark:border-zinc-700 dark:bg-zinc-800 overflow-x-auto">
            <h2 class="text-lg font-semibold mb-3 text-zinc-700 dark:text-zinc-100">Recent Stock Additions</h2>
            <table class="w-full text-sm text-zinc-700 dark:text-zinc-200">
                <thead>
                    <tr>
                        <th class="text-left">Medicine</th>
                        <th class="text-left">Qty</th>
                        <th class="text-left">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentStockAdditions as $stock)
                        <tr>
                            <td>{{ $stock->name }}</td>
                            <td>{{ $stock->quantity }}</td>
                            <td>{{ \Carbon\Carbon::parse($stock->created_at)->format('d M Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="rounded-2xl border border-zinc-200 bg-white p-4 shadow dark:border-zinc-700 dark:bg-zinc-800">
            <h2 class="text-lg font-semibold mb-3 text-zinc-700 dark:text-zinc-100">Sales Trends (Past 7 Days)</h2>
            <div id="salesTrendChart" class="h-64" wire:ignore></div>
        </div>

        <div class="rounded-2xl border border-zinc-200 bg-white p-4 shadow dark:border-zinc-700 dark:bg-zinc-800">
            <h2 class="text-lg font-semibold mb-3 text-zinc-700 dark:text-zinc-100">Inventory by Category</h2>
            <div id="inventoryChart" class="h-64" wire:ignore></div>
        </div>
    </div>
</div>

@once
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            // Sales Trend Chart
            const salesOptions = {
                chart: {
                    type: 'line',
                    height: 250,
                    toolbar: { show: false }
                },
                series: [{
                    name: 'Sales (Ksh)',
                    data: @json($salesData)
                }],
                xaxis: {
                    categories: @json($salesDates),
                    labels: {
                        style: {
                            colors: '#64748b'
                        }
                    }
                },
                colors: ['#3b82f6'],
                dataLabels: {
                    enabled: true,
                    formatter: val => 'Ksh ' + val.toLocaleString()
                },
                tooltip: {
                    y: {
                        formatter: val => 'Ksh ' + val.toLocaleString()
                    }
                }
            };
            new ApexCharts(document.querySelector("#salesTrendChart"), salesOptions).render();

            // Inventory by Category Chart
            const inventoryOptions = {
                chart: {
                    type: 'bar',
                    height: 250,
                    toolbar: { show: false }
                },
                series: [{
                    name: 'Quantity',
                    data: @json($inventoryQuantities)
                }],
                xaxis: {
                    categories: @json($inventoryCategories),
                    labels: {
                        style: {
                            colors: '#64748b'
                        }
                    }
                },
                colors: ['#10b981'],
                dataLabels: {
                    enabled: true,
                    formatter: val => val.toLocaleString()
                },
                tooltip: {
                    y: {
                        formatter: val => val.toLocaleString()
                    }
                }
            };
            new ApexCharts(document.querySelector("#inventoryChart"), inventoryOptions).render();
        </script>
    @endpush
@endonce
