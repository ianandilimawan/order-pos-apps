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
        .header h2 { font-size: 12px; margin: 5px 0; font-weight: bold; border-bottom: 1px solid #000; display: inline-block; padding-bottom: 2px;}
        .header p { margin: 0; font-size: 10px; }
        
        table { width: 100%; border-collapse: collapse; }
        td { padding: 1px 0; vertical-align: top; font-size: 11px; }
        .qty { width: 15%; }
        .name { width: 45%; padding-right: 2px; }
        .price { width: 40%; text-align: right; }
        
        /* Kitchen layout uses more space for names */
        .k-qty { width: 15%; font-weight: bold;}
        .k-name { width: 85%; font-weight: bold; font-size: 12px;}
        
        .totals { margin-top: 5px; }
        .totals table { width: 100%; }
        .totals td { font-size: 11px; }
        
        .footer { margin-top: 10px; text-align: center; font-size: 10px; }
        
        /* Hide print button when printing */
        @media print {
            .no-print { display: none; }
            body { padding: 0; width: 100%; }
            .page-break { page-break-after: always; margin-bottom: 10px; }
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

    <!-- ================= 1. CUSTOMER RECEIPT ================= -->
    <div class="header text-center">
        <h1>{{ config('app.name', 'CAFE POS') }}</h1>
        <p>{{ $order->created_at->format('d/m/Y H:i') }}</p>
        <p>NO: {{ $order->order_number }}</p>
        @if($order->customer_name)
            <p>NAMA: <span class="font-bold">{{ strtoupper($order->customer_name) }}</span></p>
        @endif
        @if($order->customer_phone)
            <p>TELP: {{ $order->customer_phone }}</p>
        @endif
        @if($order->customer_email)
            <p>EMAIL: {{ $order->customer_email }}</p>
        @endif
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
            <td class="name">
                {{ $item->product ? $item->product->name : 'Product' }}
                @if($item->notes)
                    <br><span style="font-size: 10px;">Notes: {{ $item->notes }}</span>
                @endif
            </td>
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
        <p class="font-bold">Status: {{ strtoupper($order->payment_status) }} ({{ strtoupper($order->payment_method ?? '-') }})</p>
        <p>Thank you for your visit!</p>
    </div>

    <div class="page-break"></div>

    <!-- ================= 2. CASHIER RECEIPT ================= -->
    <div class="header text-center">
        <h2>ARSIP KASIR</h2>
        <p>{{ $order->created_at->format('d/m/Y H:i') }}</p>
        <p class="font-bold">NO: {{ $order->order_number }}</p>
        @if($order->customer_name)
            <p>NAMA: <span class="font-bold">{{ strtoupper($order->customer_name) }}</span></p>
        @endif
        @if($order->customer_phone)
            <p>TELP: {{ $order->customer_phone }}</p>
        @endif
        @if($order->customer_email)
            <p>EMAIL: {{ $order->customer_email }}</p>
        @endif
        <p>TIPE: {{ $order->order_type == 'dine_in' ? 'DINE IN' : 'TAKE AWAY' }} 
            @if($order->diningTable)
                <br><span style="font-size: 14px; font-weight: bold;">MEJA {{ $order->diningTable->number }}</span>
            @endif
        </p>
    </div>

    <div class="divider"></div>

    <table>
        @foreach($order->items as $item)
        <tr>
            <td class="qty">{{ $item->quantity }}x</td>
            <td class="name">
                {{ $item->product ? $item->product->name : 'Product' }}
                @if($item->notes)
                    <br><span style="font-size: 10px;">Notes: {{ $item->notes }}</span>
                @endif
            </td>
            <td class="price">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </table>

    <div class="divider"></div>

    <div class="totals">
        <table>
            <tr>
                <td class="font-bold">TOTAL</td>
                <td class="font-bold text-right">{{ number_format($order->total, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="font-bold">PEMBAYARAN</td>
                <td class="font-bold text-right">{{ strtoupper($order->payment_method ?? '-') }}</td>
            </tr>
        </table>
    </div>
    
    <div class="footer">
        <p>Kasir: {{ $order->user ? $order->user->name : '-' }}</p>
    </div>

    <div class="page-break"></div>

    <!-- ================= 3. KITCHEN TICKET ================= -->
    <div class="header text-center">
        <h2>TIKET DAPUR</h2>
        <p>{{ $order->created_at->format('d/m/Y H:i') }}</p>
        <p class="font-bold">NO: {{ $order->order_number }}</p>
        @if($order->customer_name)
            <p style="font-size: 12px; font-weight: bold; margin-top: 5px;">NAMA: {{ strtoupper($order->customer_name) }}</p>
        @endif
        @if($order->customer_phone)
            <p style="font-size: 10px;">TELP: {{ $order->customer_phone }}</p>
        @endif
        @if($order->customer_email)
            <p style="font-size: 10px;">EMAIL: {{ $order->customer_email }}</p>
        @endif
        <p style="font-size: 12px; font-weight: bold; margin-top: 5px;">
            {{ $order->order_type == 'dine_in' ? 'DINE IN' : 'TAKE AWAY' }} 
            @if($order->diningTable)
                <br><span style="font-size: 18px;">MEJA {{ $order->diningTable->number }}</span>
            @endif
        </p>
    </div>

    <div class="divider"></div>

    <table>
        @foreach($order->items as $item)
        <tr>
            <td class="k-qty">{{ $item->quantity }}x</td>
            <td class="k-name">
                {{ $item->product ? $item->product->name : 'Product' }}
                @if($item->notes)
                    <br><span style="font-weight: normal; font-size: 10px;">Notes: {{ $item->notes }}</span>
                @endif
            </td>
        </tr>
        @endforeach
    </table>
    
    <div class="divider"></div>
    <div class="footer">
        <p>--- End of Order ---</p>
    </div>

</body>
</html>
