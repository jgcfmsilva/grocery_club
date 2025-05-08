<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Receipt #{{ $order->id }}</title>
    <style>
        @page { margin: 30px; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 40px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        .total {
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 0.9em;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Grocery Club</h1>
        <h2>Order Receipt #{{ $order->id }}</h2>
        <p>Date: {{ $order->created_at->format('d/m/Y H:i') }}</p>
    </div>

    <div>
        <p><strong>Member:</strong> {{ $order->member->name }}</p>
        @if($order->nif)
            <p><strong>NIF Number:</strong> {{ $order->nif }}</p>
        @endif
        <p><strong>Delivery Address:</strong> {{ $order->delivery_address }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Discount</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ number_format($item->unit_price, 2) }}€</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->discount, 2) }}€</td>
                <td>{{ number_format($item->subtotal, 2) }}€</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="total">Items Total:</td>
                <td class="total">{{ number_format($order->total_items, 2) }}€</td>
            </tr>
            <tr>
                <td colspan="4" class="total">Discounts:</td>
                <td class="total">{{ number_format($order->calculate_order_total_discount(), 2) }}€</td>
            </tr>
            <tr>
                <td colspan="4" class="total">Shipping:</td>
                <td class="total">{{ number_format($order->shipping_cost, 2) }}€</td>
            </tr>
            <tr>
                <td colspan="4" class="total">Order Total:</td>
                <td class="total">{{ number_format($order->total, 2) }}€</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Thank you for shopping with Grocery Club!</p>
        <p>Generated on: {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
