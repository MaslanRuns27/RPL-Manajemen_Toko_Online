<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'transaction_date', 'status', 'code'];

     protected static function booted(): void
    {
        static::creating(function ($transaction) {
            $date = now()->format('Ymd');
            $last = self::whereDate('created_at', now())->orderBy('id', 'desc')->first();

            $number = 1;
            if ($last && $last->code && preg_match('/\d{8}-(\d+)/', $last->code, $matches)) {
                $number = intval($matches[1]) + 1;
            }

            $transaction->code = 'TRX-' . $date . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
        });
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function items() {
        return $this->hasMany(TransactionItem::class);
    }

    public function payment() {
        return $this->hasOne(Payment::class);
    }
    public function getTotalAttribute()
    {
        return $this->items->sum('subtotal');
    }
}
