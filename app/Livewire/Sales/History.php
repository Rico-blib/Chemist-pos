<?php

namespace App\Livewire\Sales;

use Livewire\Component;
use App\Models\Sale;
use Illuminate\Support\Carbon;
use Livewire\WithPagination;

class History extends Component
{
    use WithPagination;

    public $search = '';
    public $from_date;
    public $to_date;
    public $filtered = false;

    public function filterSales()
    {
        $this->resetPage(); // Reset pagination when applying filters
        $this->filtered = true;
    }
    public function resetFilters()
    {
        $this->reset(['search', 'from_date', 'to_date', 'filtered']);
    }


    public function render()
    {
        $query = Sale::query()
            ->with('items.medicine')
            ->latest();

        if ($this->filtered) {
            if ($this->search) {
                $query->whereHas('items.medicine', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('id', 'like', '%' . $this->search . '%');
                });
            }

            if ($this->from_date) {
                $query->whereDate('created_at', '>=', Carbon::parse($this->from_date));
            }

            if ($this->to_date) {
                $query->whereDate('created_at', '<=', Carbon::parse($this->to_date));
            }
        }

        return view('livewire.sales.history', [
            'sales' => $query->paginate(10),
        ]);
    }

    public function print($saleId)
    {
        return redirect()->route('sales.receipt', ['sale' => $saleId]);
    }
}
