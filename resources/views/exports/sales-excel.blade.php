<table>
    <thead>
        <tr>
            <th>Sale ID</th>
            <th>Date</th>
            <th>Total (₦)</th>
            <th>Items</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($sales as $sale)
            <tr>
                <td>{{ $sale->id }}</td>
                <td>{{ $sale->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ number_format($sale->total, 2) }}</td>
                <td>
                    @foreach ($sale->items as $item)
                        {{ $item->medicine->name }} x {{ $item->quantity }}@if (!$loop->last), @endif
                    @endforeach
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
