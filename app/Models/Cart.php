<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id', 'fruit_id', 'quantity'];

    public function fruit()
    {
        return $this->belongsTo(Fruit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getSubtotalAttribute()
    {
        return $this->fruit->price * $this->quantity;
    }
}
