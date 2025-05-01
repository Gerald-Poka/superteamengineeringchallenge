<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sale Receipt #{{ $sale->id }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            line-height: 1.6;
            color: #4a4a4a;
            margin: 0;
            padding: 20px;
        }
        .receipt {
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #78350f;
            padding-bottom: 20px;
        }
        .info {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #d6d3d1;
        }
        th {
            background-color: #fef3c7;
            font-weight: 600;
            color: #78350f;
        }
        .total {
            text-align: right;
            font-size: 1.1em;
            font-weight: bold;
            color: #78350f;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px solid #78350f;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <h1 style="color: #78350f; margin: 0;">Sale Receipt</h1>
            <p style="color: #92400e;">#{{ $sale->id }}</p>
        </div>

        <div class="info">
            <p><strong>Date:</strong> {{ $sale->created_at->format('F j, Y g:i A') }}</p>
            <p><strong>Items:</strong> {{ $sale->saleItems->count() }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->saleItems as $item)
                    <tr>
                        <td>
                            {{ $item->product->name }}
                            @if($item->product->description)
                                <br>
                                <small style="color: #92400e;">{{ Str::limit($item->product->description, 50) }}</small>
                            @endif
                        </td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->unit_price, 2) }}</td>
                        <td>${{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">
            Total Amount: ${{ number_format($sale->total_amount, 2) }}
        </div>

        @if($sale->notes)
            <div style="margin-top: 20px; padding: 15px; background-color: #fef3c7; border-radius: 6px;">
                <strong>Notes:</strong><br>
                {{ $sale->notes }}
            </div>
        @endif

        <button class="no-print" onclick="window.print()" style="
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #d97706;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
        ">
            Print Receipt
        </button>
    </div>

    <script>
        window.onload = function() {
            if (!window.location.hash) {
                window.location = window.location + '#loaded';
                window.location.reload();
            }
        }
    </script>
</body>
</html>