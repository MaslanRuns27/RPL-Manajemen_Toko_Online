<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pembayaran</title>
    <style>
        body {
            font-family: monospace;
            font-size: 12px;
            width: 58mm;
            margin: auto;
            padding: 0;
            color: #000;
        }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .line { border-top: 1px dashed #000; margin: 8px 0; }
        table { width: 100%; }
        td { vertical-align: top; }
        .right { text-align: right; }
    </style>
</head>
<body>

    <div class="center bold">BUAHPEDIA</div>
    <div class="center">Jl. Sehat No. 88, Indonesia</div>
    <div class="line"></div>

    <div>
        <table>
            <tr>
                <td>No. Transaksi</td>
                <td class="right">#TRX{{ $transaction->id }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td class="right">{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td>Pelanggan</td>
                <td class="right">{{ $transaction->user->name }}</td>
            </tr>
        </table>
    </div>

    <div class="line"></div>

    <table>
        @php $grandTotal = 0; @endphp
        @foreach($transaction->items as $item)
            @php
                $subtotal = $item->fruit->price * $item->quantity;
                $grandTotal += $subtotal;
            @endphp
            <tr>
                <td colspan="2">{{ $item->fruit->name }}</td>
            </tr>
            <tr>
                <td>{{ $item->quantity }} x Rp{{ number_format($item->fruit->price, 0, ',', '.') }}</td>
                <td class="right">Rp{{ number_format($subtotal, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </table>

    <div class="line"></div>

    <table>
        <tr>
            <td class="bold">Total</td>
            <td class="right bold">Rp{{ number_format($grandTotal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Status</td>
            <td class="right">{{ ucfirst($transaction->status) }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <div class="center">Terima kasih atas pembelian Anda!</div>
    <div class="center">~ Buahpedia ~</div>

</body>
</html>
