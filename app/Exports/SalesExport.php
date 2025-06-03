<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesExport implements FromCollection, WithHeadings
{
    protected $sales;

    public function __construct(Collection $sales)
    {
        $this->sales = $sales;
    }

    public function collection()
    {
        return $this->sales->map(function ($sale) {
            return [
                'Sale ID' => $sale->id,
                'Date' => $sale->created_at->format('Y-m-d H:i'),
                'Total' => $sale->total,
                'Items' => $sale->items->map(fn($item) => $item->medicine->name . ' x' . $item->quantity)->implode(', '),
            ];
        });
    }

    public function headings(): array
    {
        return ['Sale ID', 'Date', 'Total', 'Items'];
    }
}

