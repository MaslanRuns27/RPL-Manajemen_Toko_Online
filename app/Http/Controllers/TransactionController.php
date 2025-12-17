<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Models\Fruit;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TransactionController extends Controller
{
    public function create($fruit_id)
    {
        $fruit = Fruit::findOrFail($fruit_id);
        return view('transaction.create', compact('fruit'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fruit_id' => 'required|exists:fruits,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $fruit = Fruit::findOrFail($request->fruit_id);

        // Buat transaksi
        $transaction = Transaction::create([
            'user_id' => Auth::check() ? Auth::id() : 1,  // sementara pakai user id 1
            'transaction_date' => now(),
            'status' => 'pending',
        ]);

        // Buat item transaksi
        TransactionItem::create([
            'transaction_id' => $transaction->id,
            'fruit_id' => $fruit->id,
            'quantity' => $request->quantity,
            'subtotal' => $fruit->price * $request->quantity,
        ]);

        return redirect('/')->with('success', 'Transaksi berhasil dibuat!');
    }
    public function history()
    {
        $transactions = \App\Models\Transaction::with(['items.fruit', 'payment'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('transaction.history', compact('transactions'));
    }
    public function print($id)
    {
        $transaction = \App\Models\Transaction::with(['items.fruit', 'user'])->findOrFail($id);

        $pdf = PDF::loadView('transaction.receipt', [
            'transaction' => $transaction
        ]);

        return $pdf->stream('bukti-pembayaran-' . $transaction->id . '.pdf');
    }
}
