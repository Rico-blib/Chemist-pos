<div class="p-6 space-y-6">
    <!-- Top Grid -->
    <div class="grid gap-6 md:grid-cols-2">
        <!-- Sales Summary -->
        <div class="rounded-2xl border border-zinc-200 bg-white p-4 shadow dark:border-zinc-700 dark:bg-zinc-800">
            <h2 class="text-lg font-semibold text-zinc-700 dark:text-zinc-100">Sales Summary</h2>
            <div class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
                A summary of total sales per day and month.
            </div>
            <div class="mt-4 space-y-2">
                <div class="text-sm text-zinc-600 dark:text-zinc-300">
                    <strong>Today:</strong> Ksh {{ number_format($dailySales, 2) }}
                </div>
                <div class="text-sm text-zinc-600 dark:text-zinc-300">
                    <strong>This Month:</strong> Ksh {{ number_format($monthlySales, 2) }}
                </div>
            </div>

            <!-- Sales Chart -->
            <div class="mt-4" wire:ignore>
                <div id="sales-chart" class="h-64 rounded-xl bg-white dark:bg-zinc-800"></div>
            </div>
        </div>

        <!-- Top Selling Medicines -->
        <div class="rounded-2xl border border-zinc-200 bg-white p-4 shadow dark:border-zinc-700 dark:bg-zinc-800">
            <h2 class="text-lg font-semibold text-zinc-700 dark:text-zinc-100">Top Medicines</h2>
            <div class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
                List of most sold medicines by quantity.
            </div>

            <ul class="mt-4 space-y-2">
                @forelse($topMedicines as $item)
                    <li class="text-sm text-zinc-700 dark:text-zinc-200 flex justify-between">
                        <span>{{ $item->medicine->name }}</span>
                        <span class="font-medium text-zinc-500 dark:text-zinc-300">{{ $item->total_quantity }}</span>
                    </li>
                @empty
                    <li class="text-sm text-zinc-500 dark:text-zinc-400">No data available</li>
                @endforelse
            </ul>

            <!-- Chart -->
            <div class="mt-6 w-full" wire:ignore>
                <div id="top-medicines-chart" class="rounded-xl bg-white dark:bg-zinc-800 w-full"
                    style="min-height: 300px; display: block;">
                </div>
            </div>
        </div>



        <!-- Bottom Grid -->
        <div class="grid gap-6 md:grid-cols-2">
            <!-- Low Inventory Alerts -->
            <div
                class="rounded-2xl border border-yellow-300 bg-yellow-50 p-4 shadow dark:border-yellow-600 dark:bg-yellow-900">
                <h2 class="text-lg font-semibold text-yellow-800 dark:text-yellow-200">Low Inventory Alerts</h2>
                <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                    Medicines with low quantity in stock.
                </div>
                <ul class="mt-4 space-y-2">
                    @forelse($lowStock as $medicine)
                        <li class="text-sm text-yellow-800 dark:text-yellow-100 flex justify-between">
                            <span>{{ $medicine->name }}</span>
                            <span class="font-medium">Qty: {{ $medicine->quantity }}</span>
                        </li>
                    @empty
                        <li class="text-sm text-yellow-700 dark:text-yellow-200">No low stock items</li>
                    @endforelse
                </ul>
            </div>

            <!-- Export Buttons -->
            <div class="rounded-2xl border border-zinc-200 bg-white p-4 shadow dark:border-zinc-700 dark:bg-zinc-800">
                <h2 class="text-lg font-semibold text-zinc-700 dark:text-zinc-100">Export Reports</h2>
                <div class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
                    Download reports in PDF or Excel format.
                </div>
                <div class="mt-4 flex gap-4">
                    <a href="{{ route('sales.export.pdf') }}" target="_blank"
                        class="inline-flex items-center rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-red-700">
                        PDF
                    </a>
                    <a href="{{ route('sales.export.excel') }}" target="_blank"
                        class="inline-flex items-center rounded-xl bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-green-700">
                        Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

    @once
        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
            <script>
                function renderDashboardCharts() {
                    // === Sales Chart ===
                    const salesEl = document.querySelector("#sales-chart");
                    if (salesEl) {
                        salesEl.innerHTML = "";

                        const salesOptions = {
                            chart: {
                                type: 'bar',
                                height: 250,
                                toolbar: {
                                    show: false
                                }
                            },
                            series: [{
                                name: 'Sales (Ksh)',
                                data: [{{ $dailySales }}, {{ $monthlySales }}]
                            }],
                            xaxis: {
                                categories: ['Today', 'This Month'],
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

                        new ApexCharts(salesEl, salesOptions).render();
                    }

                    // === Top Medicines Horizontal Bar Chart ===
                    const topEl = document.querySelector("#top-medicines-chart");
                    const topMedicinesData = {!! json_encode($topMedicines->pluck('total_quantity')) !!};
                    const topMedicinesLabels = {!! json_encode($topMedicines->pluck('medicine.name')) !!};

                    console.log("Top Medicines Data:", topMedicinesData);
                    console.log("Top Medicines Labels:", topMedicinesLabels);

                    if (topEl) {
                        topEl.innerHTML = "";

                        if (topMedicinesData.length && topMedicinesData.some(q => q > 0)) {
                            const topOptions = {
                                chart: {
                                    type: 'bar',
                                    height: 300,
                                    toolbar: {
                                        show: false
                                    }
                                },
                                series: [{
                                    name: 'Quantity Sold',
                                    data: topMedicinesData
                                }],
                                xaxis: {
                                    categories: topMedicinesLabels,
                                    labels: {
                                        style: {
                                            colors: '#64748b'
                                        }
                                    }
                                },
                                colors: ['#10b981'],
                                dataLabels: {
                                    enabled: true,
                                    formatter: val => val + ' units'
                                },
                                tooltip: {
                                    y: {
                                        formatter: val => val + ' units'
                                    }
                                },
                                plotOptions: {
                                    bar: {
                                        horizontal: true,
                                        borderRadius: 6
                                    }
                                },
                                legend: {
                                    show: false
                                }
                            };

                            new ApexCharts(topEl, topOptions).render();
                        } else {
                            topEl.innerHTML = "<div class='text-sm text-zinc-500 text-center mt-10'>No data for chart</div>";
                        }
                    }
                }

                window.addEventListener('load', () => {
                    setTimeout(() => renderDashboardCharts(), 100);
                });

                document.addEventListener('livewire:load', function() {
                    Livewire.hook('message.processed', (message, component) => {
                        setTimeout(() => renderDashboardCharts(), 100);
                    });
                });
            </script>
        @endpush
    @endonce
