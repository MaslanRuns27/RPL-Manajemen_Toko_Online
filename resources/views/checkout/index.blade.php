@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Konfirmasi Pembayaran</h2>

    @if ($cartItems->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Buah</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartItems as $item)
                <tr>
                    <td>{{ $item->fruit->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp {{ number_format($item->fruit->price, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->fruit->price * $item->quantity, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="3" class="text-end"><strong>Total</strong></td>
                    <td><strong>Rp {{ number_format($grandTotal, 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>

        <form action="{{ route('checkout.pay') }}" method="POST">
            @csrf
            <button class="btn btn-success">Bayar Sekarang</button>
        </form>
    @else
        <div class="alert alert-warning">
            Tidak ada item di keranjang.
        </div>
    @endif
</div>
@endsection
