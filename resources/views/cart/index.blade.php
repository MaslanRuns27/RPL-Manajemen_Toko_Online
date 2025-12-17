@extends('layouts.app') {{-- Pastikan kamu punya layouts/app.blade.php atau ganti dengan HTML langsung --}}

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Keranjang Belanja</h2>

    @if($cartItems->count() > 0)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Buah</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp
                @foreach($cartItems as $item)
                    @php
                        $subtotal = $item->quantity * $item->fruit->price;
                        $grandTotal += $subtotal;
                    @endphp
                    <tr>
                        <td>{{ $item->fruit->name }}</td>
                        <td>Rp {{ number_format($item->fruit->price, 0, ',', '.') }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                        <td>
                            {{-- tombol hapus atau update bisa ditambahkan di sini --}}
                            <form action="{{ route('cart.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus item ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" class="text-end"><strong>Total</strong></td>
                    <td colspan="2"><strong>Rp {{ number_format($grandTotal, 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>

        <a href="{{ route('checkout.index') }}" class="btn btn-success">Lanjutkan ke Pembayaran</a>
    @else
        <div class="alert alert-info">
            Keranjang kamu kosong.
        </div>
    @endif
</div>
@endsection
