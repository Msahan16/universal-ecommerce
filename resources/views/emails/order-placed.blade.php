<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8fafc; color: #1e293b; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; }
        .header { background: #0f172a; color: #ffffff; padding: 25px 30px; text-align: center; }
        .content { padding: 30px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .table th { background: #f1f5f9; text-align: left; padding: 10px; font-size: 12px; text-transform: uppercase; color: #64748b; }
        .table td { padding: 12px 10px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
        .badge { background: #dbeafe; color: #1d4ed8; padding: 4px 8px; border-radius: 9999px; font-size: 12px; font-weight: bold; }
        .total-box { margin-top: 20px; padding: 15px; background: #f8fafc; border-radius: 8px; text-align: right; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="margin:0; font-size: 20px;">{{ \App\Models\SiteSetting::get('site_name', 'Universal Commerce') }}</h2>
            <p style="margin: 5px 0 0; font-size: 13px; color: #94a3b8;">Order Confirmation</p>
        </div>

        <div class="content">
            <h3 style="margin-top:0;">Hello, {{ $order->customer_name }}!</h3>
            <p style="color: #64748b; font-size: 14px; line-height: 1.5;">
                Thank you for placing your order with us. We have received order <strong>#{{ $order->order_number }}</strong> and our team is preparing it for dispatch.
            </p>

            <div style="background: #f8fafc; padding: 15px; border-radius: 8px; font-size: 13px; margin-bottom: 20px;">
                <div><strong>Order Number:</strong> {{ $order->order_number }}</div>
                <div><strong>Payment Method:</strong> {{ strtoupper($order->payment_method) }} ({{ strtoupper($order->payment_status) }})</div>
                <div><strong>Delivery Address:</strong> {{ $order->shipping_address }}, {{ $order->city }}</div>
            </div>

            <h4 style="margin-bottom: 5px;">Order Summary</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th style="text-align: center;">Qty</th>
                        <th style="text-align: right;">Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>
                                <strong>{{ $item->product_name }}</strong>
                                @if($item->variant_name)
                                    <div style="font-size: 12px; color: #64748b;">Option: {{ $item->variant_name }}</div>
                                @endif
                            </td>
                            <td style="text-align: center;">{{ $item->quantity }}</td>
                            <td style="text-align: right; font-family: monospace;">{{ \App\Models\SiteSetting::get('currency_symbol', 'Rs. ') }}{{ number_format($item->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="total-box">
                <div>Subtotal: <strong>{{ \App\Models\SiteSetting::get('currency_symbol', 'Rs. ') }}{{ number_format($order->subtotal, 2) }}</strong></div>
                @if($order->discount > 0)
                    <div style="color: #16a34a;">Discount: -{{ \App\Models\SiteSetting::get('currency_symbol', 'Rs. ') }}{{ number_format($order->discount, 2) }}</div>
                @endif
                <div>Shipping: {{ $order->shipping_fee > 0 ? \App\Models\SiteSetting::get('currency_symbol', 'Rs. ').number_format($order->shipping_fee, 2) : 'FREE' }}</div>
                <div style="font-size: 18px; margin-top: 5px; color: #2563eb;">Total: <strong>{{ \App\Models\SiteSetting::get('currency_symbol', 'Rs. ') }}{{ number_format($order->total, 2) }}</strong></div>
            </div>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} {{ \App\Models\SiteSetting::get('site_name', 'Universal Commerce') }}. All rights reserved.<br>
            {{ \App\Models\SiteSetting::get('contact_address', '') }}
        </div>
    </div>
</body>
</html>
