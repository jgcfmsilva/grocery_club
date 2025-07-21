<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Completed</title>
</head>
<body style="font-family: Arial, sans-serif; color: #222;">
    <h2 style="color: #2563eb;">Order Completed</h2>
    <p>Hello {{ $order->member->name }},</p>
    <p>Your order <strong>#{{ $order->id }}</strong> has been completed. Please find your receipt attached.</p>

    <h3 style="margin-top: 32px;">Order Details</h3>
    <table width="100%" cellpadding="8" cellspacing="0" border="1" style="border-collapse: collapse; margin-bottom: 24px;">
        <thead style="background: #f3f4f6;">
            <tr>
                <th align="left">Product</th>
                <th align="center">Qty</th>
                <th align="right">Unit Price</th>
                <th align="right">Discount</th>
                <th align="right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
        @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td align="center">{{ $item->quantity }}</td>
                <td align="right">{{ number_format($item->unit_price, 2) }}€</td>
                <td align="right">{{ $item->discount > 0 ? number_format($item->discount, 2) . '€' : '-' }}</td>
                <td align="right">{{ number_format($item->subtotal, 2) }}€</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <p>
        <strong>Subtotal:</strong> {{ number_format($order->total_items, 2) }}€<br>
        <strong>Shipping:</strong> {{ number_format($order->shipping_cost, 2) }}€<br>
        <strong>Total:</strong> <span style="color: #2563eb;">{{ number_format($order->total, 2) }}€</span><br>
        @if(method_exists($order, 'calculate_order_total_discount') && $order->calculate_order_total_discount() > 0)
            <strong>Total Discount:</strong> {{ number_format($order->calculate_order_total_discount(), 2) }}€<br>
        @endif
        <strong>Order Date:</strong> {{ $order->created_at->format('d/m/Y H:i') }}
    </p>

    <p style="margin-top: 32px;">Thank you for shopping with us!</p>
</body>
</html>
