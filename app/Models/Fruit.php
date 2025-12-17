<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fruit extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'image', 'price', 'stock', 'category_id'];

    public function category() {
        return $this->belongsTo(Category::class);
    }
    public function transactionItems() {
        return $this->hasMany(TransactionItem::class);
    }
}
