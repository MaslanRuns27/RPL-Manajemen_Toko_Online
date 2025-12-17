<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')->relationship('user', 'name')->required(),
                Forms\Components\DateTimePicker::make('transaction_date')->required(),
                Forms\Components\Select::make('status')->options([
                    'pending' => 'Pending',
                    'paid' => 'Paid',
                    'failed' => 'Failed'
                ])->required(),
                Forms\Components\Repeater::make('items')
                ->relationship()
                ->schema([
                    Forms\Components\Select::make('fruit_id')->relationship('fruit', 'name')->required(),
                    Forms\Components\TextInput::make('quantity')->numeric()->minValue(1)->required(),
                ])
                ->columns(2)
                ->createItemButtonLabel('Tambah Buah')

            ]);
            
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('user.name')->label('Customer'),
                Tables\Columns\TextColumn::make('transaction_date')->dateTime(),
                Tables\Columns\TextColumn::make('status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'pending' => 'gray',
                    'paid', 'settlement', 'capture' => 'success',
                    'failed', 'deny', 'cancel', 'expired' => 'danger',
                    default => 'secondary', // fallback jika status tidak dikenali
                })
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
