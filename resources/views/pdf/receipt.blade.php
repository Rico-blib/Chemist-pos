<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Receipt #{{ $sale->id }}</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
            font-size: 14px;
            color: #000;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }

        .logo {
            max-height: 60px;
        }

        .barcode {
            text-align: right;
        }

        .barcode img {
            max-height: 50px;
        }

        h2 {
            margin-top: 20px;
            text-align: center;
        }

        .meta {
            margin-top: 10px;
            text-align: left;
        }

        .items {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
        }

        .items th,
        .items td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .total {
            text-align: right;
            margin-top: 10px;
            font-weight: bold;
            font-size: 16px;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
    </style>
</head>

<body>

    <div class="header">
        <div>
            @if (!empty($autoPrint))
                <img src="{{ asset('images/pharmacy-logo.png') }}" alt="Pharmacy Logo" class="logo">
            @else
                <img src="{{ public_path('images/pharmacy-logo.png') }}" alt="Pharmacy Logo" class="logo">
            @endif

        </div>
        <div class="barcode">
            @if (!empty($autoPrint))
                <img src="{{ asset('temp/barcode-' . $sale->id . '.png') }}" alt="Barcode">
            @else
                <img src="{{ $barcodePath }}" alt="Barcode">
            @endif

        </div>
    </div>

    <h2>Sales Receipt</h2>

    <div class="meta">
        <strong>Date:</strong> {{ $sale->created_at->format('Y-m-d H:i') }}
    </div>

    <table class="items">
        <thead>
            <tr>
                <th>Medicine</th>
                <th>Qty</th>
                <th>Price (₦)</th>
                <th>Subtotal (₦)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sale->items as $item)
                <tr>
                    <td>{{ $item->medicine->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price, 2) }}</td>
                    <td>{{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Total: ₦{{ number_format($sale->total, 2) }}
    </div>

    <div class="footer">
        Thank you for your purchase!<br>
        Powered by Pharmacy POS System
    </div>
    @if (!empty($autoPrint))
        <script>
            window.onload = function() {
                window.print();
            };
        </script>
    @endif

</body>

</html>
