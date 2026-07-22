<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $order->order_number }}</title>
    <style>
        @page {
            margin: 0;
            size: 58mm auto;
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            width: 58mm;
            margin: 0 auto;
            padding: 5px;
            color: #000;
            background: #fff;
            line-height: 1.1;
            text-transform: uppercase;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        
        .divider {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }
        .header { margin-bottom: 10px; }
        .header h1 { font-size: 14px; margin: 0 0 5px 0; font-weight: bold; }
        .header p { margin: 0; font-size: 10px; }
        
        table { width: 100%; border-collapse: collapse; }
        td { padding: 1px 0; vertical-align: top; font-size: 11px; }
        .qty { width: 15%; }
        .name { width: 45%; padding-right: 2px; }
        .price { width: 40%; text-align: right; }
        
        .totals { margin-top: 5px; }
        .totals table { width: 100%; }
        .totals td { font-size: 11px; }
        
        .footer { margin-top: 10px; text-align: center; font-size: 10px; }
        
        /* Hide print button when printing */
        @media print {
            .no-print { display: none; }
            body { padding: 0; width: 100%; }
        }
        
        .print-btn-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .print-btn {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            font-family: sans-serif;
        }
        .print-btn:hover { background: #2563eb; }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print print-btn-container">
        <button class="print-btn" onclick="window.print()">Print Receipt</button>
        <button class="print-btn" style="background:#6b7280; margin-left:10px;" onclick="window.close()">Close</button>
    </div>

    <div class="header text-center">
        <h1>{{ config('app.name', 'CAFE POS') }}</h1>
        <p>{{ $order->created_at->format('d/m/Y H:i') }}</p>
        <p>NO: {{ $order->order_number }}</p>
        <p>TIPE: {{ $order->order_type == 'dine_in' ? 'DINE IN' : 'TAKE AWAY' }} 
            @if($order->diningTable)
                - MEJA {{ $order->diningTable->number }}
            @endif
        </p>
    </div>

    <div class="divider"></div>

    <table>
        @foreach($order->items as $item)
        <tr>
            <td class="qty">{{ $item->quantity }}x</td>
            <td class="name">{{ $item->product ? $item->product->name : 'Product' }}</td>
            <td class="price">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </table>

    <div class="divider"></div>

    <div class="totals">
        <table>
            <tr>
                <td>Subtotal</td>
                <td class="text-right">{{ number_format($order->subtotal, 0, ',', '.') }}</td>
            </tr>
            @foreach($order->charges as $charge)
            <tr>
                <td>{{ $charge->charge_name }}</td>
                <td class="text-right">{{ number_format($charge->charge_amount, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr>
                <td class="font-bold">TOTAL</td>
                <td class="font-bold text-right">{{ number_format($order->total, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="divider"></div>
    
    <div class="footer">
        <p class="font-bold">Status: {{ strtoupper($order->payment_status) }}</p>
        <p>Thank you for your visit!</p>
    </div>

</body>
</html>
