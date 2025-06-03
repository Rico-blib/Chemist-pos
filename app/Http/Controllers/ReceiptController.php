<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Milon\Barcode\Facades\DNS1DFacade as DNS1D;

class ReceiptController extends Controller
{
    public function show(Sale $sale)
    {
        $sale->load('items.medicine');
        return view('sales.receipt', compact('sale'));
    }

    public function download(Sale $sale)
    {
        $sale->load('items.medicine');

        $barcode = DNS1D::getBarcodePNG((string) $sale->id, 'C128');
        $barcodePath = public_path("temp/barcode-{$sale->id}.png");

        if (!file_exists(public_path('temp'))) {
            mkdir(public_path('temp'), 0755, true);
        }

        file_put_contents($barcodePath, base64_decode($barcode));

        $pdf = Pdf::loadView('pdf.receipt', [
            'sale' => $sale,
            'barcodePath' => $barcodePath,
        ]);

        return $pdf->download("receipt-{$sale->id}.pdf");
    }

    public function print(Sale $sale)
    {
        $sale->load('items.medicine');

        $barcode = DNS1D::getBarcodePNG((string) $sale->id, 'C128');
        $barcodePath = public_path("temp/barcode-{$sale->id}.png");

        if (!file_exists(public_path('temp'))) {
            mkdir(public_path('temp'), 0755, true);
        }

        file_put_contents($barcodePath, base64_decode($barcode));

        return view('pdf.receipt', [
            'sale' => $sale,
            'barcodePath' => $barcodePath,
            'autoPrint' => true,
        ]);
    }
}
