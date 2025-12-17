<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Transaction;
use Midtrans\Config;
use App\Models\Cart;
use App\Models\Transaction as Trans;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        $carts = Cart::with('fruit')->where('user_id', Auth::id())->get();
        $total = $carts->sum(fn ($item) => $item->fruit->price * $item->quantity);

        return view('checkout.index', [
        'cartItems' => $carts,
        'grandTotal' => $total
        ]);
    }

    public function pay(Request $request)
    {
        $user = Auth::user();
        $carts = Cart::with('fruit')->where('user_id', $user->id)->get();
        $total = $carts->sum(fn ($item) => $item->fruit->price * $item->quantity);

        // Buat Transaction lokal
        $trx = Trans::create([
            'user_id' => $user->id,
            'transaction_date' => now(),
            'status' => 'pending',
        ]);

        foreach ($carts as $cart) {
            TransactionItem::create([
                'transaction_id' => $trx->id,
                'fruit_id' => $cart->fruit_id,
                'quantity' => $cart->quantity,
                'subtotal' => $cart->fruit->price * $cart->quantity,
            ]);
        }

        // Midtrans Payload
        $payload = [
            'transaction_details' => [
                'order_id' => 'TRX-' . $trx->id . '-' . time(),
                'gross_amount' => $total,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
        ];

        $snapToken = Snap::getSnapToken($payload);

        return view('checkout.payment', [
            'snapToken' => $snapToken,
            'trx' => $trx,
        ]);
    }
    public function finish(Request $request)
    {
        $result = json_decode($request->input('payment_result'), true);
        $transactionId = $request->input('transaction_id');

        // Update status transaksi
        $transaction = \App\Models\Transaction::find($transactionId);
        $transaction->status = $result['transaction_status'] ?? 'pending';
        $transaction->save();

        // Kurangi stok buah berdasarkan item transaksi
        foreach ($transaction->items as $item) {
            $fruit = $item->fruit;
            if ($fruit) {
                $fruit->stock -= $item->quantity;
                $fruit->save();
            }
        }

        // Hapus isi keranjang
        Cart::where('user_id', Auth::id())->delete();

        return redirect('/')->with('success', 'Pembayaran berhasil diproses.');
        if (!$transaction) {
            return redirect('/')->with('error', 'Transaksi tidak ditemukan.');
        }


    }
    public function __construct()
    {
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = env('MIDTRANS_PRODUCTION') === 'true'; // Hasilnya akan false
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
    }
    public function store(Request $request)
    {
        return redirect()->route('checkout.pay');
    }

}