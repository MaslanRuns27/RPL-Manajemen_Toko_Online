<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Fruit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('fruit')
            ->where('user_id', Auth::id())
            ->get();

        return view('cart.index', compact('cartItems'));
    }

    public function store(Request $request)
    {
        $fruit = Fruit::findOrFail($request->fruit_id);

        if ($request->quantity > $fruit->stock) {
            return back()->with('error', 'Jumlah melebihi stok yang tersedia.');
        }
        $cart = Cart::updateOrCreate(
            ['user_id' => Auth::id(), 'fruit_id' => $request->fruit_id],
            ['quantity' => DB::raw('quantity + ' . $request->quantity)]
        );

        return redirect()->route('cart.index')->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function destroy(Cart $cart)
    {
        $cart->delete();
        return back()->with('success', 'Item dihapus dari keranjang.');
    }
}
