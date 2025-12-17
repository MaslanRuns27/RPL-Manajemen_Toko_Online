<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\TransactionItem;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TransactionItemResource\Pages;
use App\Filament\Resources\TransactionItemResource\RelationManagers;

class TransactionItemResource extends Resource
{
    protected static ?string $model = TransactionItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('transaction_id')
                ->relationship('transaction', 'code')
                ->required(),

                Forms\Components\Select::make('fruit_id')
                ->relationship('fruit', 'name')
                ->required()
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $fruit = \App\Models\Fruit::find($state);
                    $quantity = $get('quantity') ?? 1;
                    if ($fruit) {
                        $set('subtotal', $fruit->price * $quantity);
                    }
                }),
                Forms\Components\TextInput::make('quantity')
                ->numeric()
                ->required()
                ->minValue(1)
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $fruit = \App\Models\Fruit::find($get('fruit_id'));
                    if ($fruit) {
                        $set('subtotal', $fruit->price * $state);
                    }
                }),
                Forms\Components\TextInput::make('subtotal') 
                    ->numeric()
                    ->disabled() // Supaya tidak bisa diketik manual
                    ->dehydrated() // Supaya tetap disimpan
            ]);
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        $fruit = \App\Models\Fruit::find($data['fruit_id']);
        $quantity = $data['quantity'] ?? 1; // default 1 jika kosong
        $data['subtotal'] = $fruit->price * $quantity;
        return $data;
    }

    public static function mutateFormDataBeforeUpdate(array $data): array
    {
        $record = TransactionItem::find(request()->route('record'));

    $oldFruitId = $record->fruit_id;
    $oldQuantity = $record->quantity;

    $newFruitId = $data['fruit_id'];
    $newQuantity = $data['quantity'] ?? 1;

    // Jika buahnya sama
    if ($oldFruitId == $newFruitId) {
        $diff = $newQuantity - $oldQuantity;

        if ($diff > 0) {
            // Tambah jumlah beli → stok dikurangi
            \App\Models\Fruit::find($newFruitId)->decrement('stock', $diff);
        } elseif ($diff < 0) {
            // Kurang jumlah beli → stok dikembalikan
            \App\Models\Fruit::find($newFruitId)->increment('stock', abs($diff));
        }
    } else {
        // Buah berbeda → kembalikan stok lama, kurangi stok baru
        \App\Models\Fruit::find($oldFruitId)->increment('stock', $oldQuantity);
        \App\Models\Fruit::find($newFruitId)->decrement('stock', $newQuantity);
    }

    // Subtotal tetap dihitung ulang
    $fruit = \App\Models\Fruit::find($newFruitId);
    $data['subtotal'] = $fruit->price * $newQuantity;

    return $data;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transaction.code')->label('Kode Transaksi'),
                Tables\Columns\TextColumn::make('fruit.name'),
                Tables\Columns\TextColumn::make('quantity'),
                Tables\Columns\TextColumn::make('subtotal')->money('IDR')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()
                    ->color('primary'),
                    Tables\Actions\DeleteAction::make(),
                ])
                ->label('More actions')
                ->icon('heroicon-m-ellipsis-vertical')
                ->size(ActionSize::Small)
                ->color('primary')
                ->button()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactionItems::route('/'),
            'create' => Pages\CreateTransactionItem::route('/create'),
            'edit' => Pages\EditTransactionItem::route('/{record}/edit'),
        ];
    }
}
