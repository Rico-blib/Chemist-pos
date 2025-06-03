<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\SalesExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    // Export to PDF
    public function exportPdf(Request $request)
    {
        $sales = $this->getFilteredSales($request);

        return Pdf::loadView('pdf.sales-export', ['sales' => $sales])
            ->download('sales-report.pdf');
    }

    // Export to Excel
    public function exportExcel(Request $request)
    {
        $sales = $this->getFilteredSales($request);

        return Excel::download(new SalesExport($sales), 'sales-report.xlsx');
    }


    // Shared logic for filtering
    protected function getFilteredSales(Request $request)
    {
        $query = Sale::with('items.medicine')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('items.medicine', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            });
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        return $query->get();
    }
}
