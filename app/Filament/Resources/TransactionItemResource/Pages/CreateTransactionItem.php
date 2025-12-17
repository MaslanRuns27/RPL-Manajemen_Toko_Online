<?php

namespace App\Filament\Resources\TransactionItemResource\Pages;

use App\Filament\Resources\TransactionItemResource;
use App\Models\TransactionItem;
use App\Models\Fruit;
use Filament\Resources\Pages\CreateRecord;

class CreateTransactionItem extends CreateRecord
{
    protected static string $resource = TransactionItemResource::class;

    protected function handleRecordCreation(array $data): TransactionItem
    {
        $fruit = Fruit::findOrFail($data['fruit_id']);

        // Cek stok cukup
        if ($fruit->stock < $data['quantity']) {
            throw new \Exception('Stok buah tidak mencukupi.');
        }

        // Kurangi stok
        $fruit->decrement('stock', $data['quantity']);

        // Hitung subtotal
        $data['subtotal'] = $fruit->price * $data['quantity'];

        // Simpan transaksi item
        return TransactionItem::create($data);
    }
}
