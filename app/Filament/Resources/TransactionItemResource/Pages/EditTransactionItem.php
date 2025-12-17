<?php

namespace App\Filament\Resources\TransactionItemResource\Pages;

use App\Filament\Resources\TransactionItemResource;
use App\Models\Fruit;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransactionItem extends EditRecord
{
    protected static string $resource = TransactionItemResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $fruit = Fruit::find($data['fruit_id']);
        $data['subtotal'] = $fruit->price * $data['quantity'];
        return $data;
    }

    protected function afterSave(): void
    {
        // dd('afterSave jalan');
        $record = $this->record;

        $oldRecord = $record->fresh(); // Ambil data setelah disimpan
        $original = $record->getOriginal(); // Ambil data sebelum update

        $oldFruitId = $original['fruit_id'];
        $oldQuantity = $original['quantity'];

        $newFruitId = $record->fruit_id;
        $newQuantity = $record->quantity;

        if ($oldFruitId === $newFruitId) {
            $selisih = $newQuantity - $oldQuantity;
            Fruit::find($newFruitId)->decrement('stock', $selisih);
        } else {
            Fruit::find($oldFruitId)->increment('stock', $oldQuantity);
            Fruit::find($newFruitId)->decrement('stock', $newQuantity);
        }
    }
}
