<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sale;
use App\Models\Medicine;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public function render()
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();

        // KPIs
        $totalSalesToday = Sale::whereDate('created_at', $today)->sum('total');
        $monthlyRevenue = Sale::whereBetween('created_at', [$startOfMonth, now()])->sum('total');
        $transactionsToday = Sale::whereDate('created_at', $today)->count();

        // Top Selling
        $topSellingMedicine = DB::table('sale_items')
            ->select('medicine_id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('medicine_id')
            ->orderByDesc('total_sold')
            ->first();

        if ($topSellingMedicine) {
            $topSellingMedicine->medicine = Medicine::find($topSellingMedicine->medicine_id);
        }

        // Inventory alerts
        $lowStockMedicines = Medicine::where('quantity', '<', 10)->get(); // threshold = 10
        $expiringMedicines = Medicine::whereDate('expiry_date', '<=', now()->addDays(30))->get();

        // Recent activity
        $recentSales = Sale::latest()->take(5)->get();
        $recentStockAdditions = Medicine::latest()->take(5)->get(); // Since no Stock model, fallback to recent medicines

        // Chart Data: Sales Past 7 Days
        $salesDates = collect(range(6, 0))->map(function ($daysAgo) {
            return now()->subDays($daysAgo)->format('Y-m-d');
        });

        $salesData = $salesDates->map(function ($date) {
            return Sale::whereDate('created_at', $date)->sum('total');
        });

        // Chart Data: Inventory by Category
        $inventoryByCategory = Medicine::select('category', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('category')
            ->get();

        $inventoryCategories = $inventoryByCategory->pluck('category');
        $inventoryQuantities = $inventoryByCategory->pluck('total_quantity');

        return view('livewire.dashboard', [
            'totalSalesToday' => $totalSalesToday,
            'monthlyRevenue' => $monthlyRevenue,
            'transactionsToday' => $transactionsToday,
            'topSellingMedicine' => $topSellingMedicine,
            'lowStockMedicines' => $lowStockMedicines,
            'expiringMedicines' => $expiringMedicines,
            'recentSales' => $recentSales,
            'recentStockAdditions' => $recentStockAdditions,
            'salesDates' => $salesDates,
            'salesData' => $salesData,
            'inventoryCategories' => $inventoryCategories,
            'inventoryQuantities' => $inventoryQuantities,
        ]);
    }
}
