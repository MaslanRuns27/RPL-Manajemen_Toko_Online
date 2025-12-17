<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Fruit;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\FruitResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\FruitResource\RelationManagers;
use Filament\Tables\Columns\ImageColumn;
use Filament\Support\Enums\ActionSize;


class FruitResource extends Resource
{
    protected static ?string $model = Fruit::class;

    protected static ?string $navigationGroup = 'Data Buah';
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationLabel = 'Buah';
    public static ?string $label = 'Buah';
    protected static ?string $slug = 'Buah';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('image')->image()->directory('fruits')->columnSpan(2),
                TextInput::make('name')->required(),
                TextInput::make('price')->numeric()->required()->prefix('RP'),
                TextInput::make('stock')->numeric()->required(),
                Select::make('category_id')->relationship('category', 'name')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->stacked(),
                TextColumn::make('name')->sortable(),
                TextColumn::make('price')->money('IDR'),
                TextColumn::make('stock'),
                TextColumn::make('category.name')->searchable()
                
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
            'index' => Pages\ListFruits::route('/'),
            'create' => Pages\CreateFruit::route('/create'),
            'edit' => Pages\EditFruit::route('/{record}/edit'),
        ];
    }
}
