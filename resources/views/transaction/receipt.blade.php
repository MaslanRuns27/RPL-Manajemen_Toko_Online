<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Pembayaran</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; color: #333; }
        h2 { text-align: center; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 8px; border: 1px solid #ddd; }
        th { background-color: #f1f1f1; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>

    <h2>Bukti Pembayaran - Buahpedia</h2>
    
    <p><strong>Nama:</strong> {{ $transaction->user->name }}</p>
    <p><strong>Email:</strong> {{ $transaction->user->email }}</p>
    <p><strong>Tanggal Transaksi:</strong> {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d M Y, H:i') }}</p>
    <p><strong>Status:</strong> {{ ucfirst($transaction->status) }}</p>

    <table>
        <thead>
            <tr>
                <th>Nama Buah</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach($transaction->items as $item)
                @php $subtotal = $item->fruit->price * $item->quantity; $grandTotal += $subtotal; @endphp
                <tr>
                    <td>{{ $item->fruit->name }}</td>
                    <td>Rp {{ number_format($item->fruit->price, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr>
                <th colspan="3" class="text-right">Total</th>
                <th class="text-right">Rp {{ number_format($grandTotal, 0, ',', '.') }}</th>
            </tr>
        </tbody>
    </table>

    <p class="text-center" style="margin-top: 30px;">Terima kasih telah berbelanja di Buahpedia </p>

</body>
</html>
