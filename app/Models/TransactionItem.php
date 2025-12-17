<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionItem extends Model
{
    use HasFactory;
    
    protected $fillable = ['transaction_id', 'fruit_id', 'quantity', 'subtotal'];

    protected static function booted(): void
{
    static::creating(function ($item) {
        $fruit = Fruit::find($item->fruit_id);

        if ($fruit->stock < $item->quantity) {
            throw new \Exception('Stok tidak cukup.');
        }

        $fruit->decrement('stock', $item->quantity);
        $item->subtotal = $fruit->price * $item->quantity;
    });

    static::updating(function ($item) {
    $original = $item->getOriginal();

    $oldFruitId = $original['fruit_id'];
    $oldQty = $original['quantity'];

    $newFruitId = $item->fruit_id;
    $newQty = $item->quantity;

    if ($oldFruitId == $newFruitId) {
        $diff = $newQty - $oldQty;

        if ($diff > 0) {
            // Jika jumlah naik, kurangi stock
            Fruit::find($newFruitId)->decrement('stock', $diff);
        } elseif ($diff < 0) {
            // Jika jumlah turun, kembalikan stock
            Fruit::find($newFruitId)->increment('stock', abs($diff));
        }

    } else {
        // Ganti buah: kembalikan stok buah lama, kurangi stok buah baru
        Fruit::find($oldFruitId)->increment('stock', $oldQty);
        Fruit::find($newFruitId)->decrement('stock', $newQty);
    }

    // Hitung ulang subtotal
    $item->subtotal = Fruit::find($newFruitId)->price * $newQty;
    });

    }

    public function transaction() {
        return $this->belongsTo(Transaction::class);
    }

    public function fruit() {
        return $this->belongsTo(Fruit::class);
    }
}
