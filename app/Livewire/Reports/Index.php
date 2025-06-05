<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Medicine;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    public $dailySales = 0;
    public $monthlySales = 0;
    public $topMedicines = [];
    public $lowStock = [];

    public function mount()
    {
        // Total Sales Today
        $this->dailySales = Sale::whereDate('created_at', today())->sum('total');

        // Total Sales This Month
        $this->monthlySales = Sale::whereMonth('created_at', now()->month)->sum('total');

        // Top 5 Sold Medicines
        $this->topMedicines = SaleItem::select('medicine_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('medicine_id')
            ->orderByDesc('total_quantity')
            ->take(5)
            ->with('medicine')
            ->get();

        // Low Stock Medicines (threshold = 10)
        $this->lowStock = Medicine::where('quantity', '<', 10)->get();
        
    }

    public function render()
    {
        return view('livewire.reports.index');
    }
}
