@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Riwayat Transaksi</h2>

    @forelse ($transactions as $transaction)
        <div class="card mb-4">
            <div class="card-header">
                <strong>Order ID:</strong> TRX-{{ $transaction->id }} |
                <strong>Tanggal:</strong> {{ $transaction->transaction_date }} |
                <strong>Status:</strong>
                <span class="badge bg-{{ $transaction->status == 'settlement' ? 'success' : ($transaction->status == 'pending' ? 'warning' : 'danger') }}">
                    {{ ucfirst($transaction->status) }}
                </span>
            </div>
            <div class="card-body">
                <ul>
                    @foreach ($transaction->items as $item)
                        <li>{{ $item->fruit->name }} - {{ $item->quantity }} x Rp{{ number_format($item->fruit->price, 0, ',', '.') }}</li>
                    @endforeach
                </ul>
                <div class="mt-2"><strong>Total: </strong>Rp {{ number_format($transaction->total, 0, ',', '.') }}</div>
                <a href="{{ route('transaction.print', $transaction->id) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                    Cetak PDF
                </a>
            </div>
        </div>
    @empty
        <div class="alert alert-info">Belum ada transaksi.</div>
    @endforelse
</div>
@endsection
