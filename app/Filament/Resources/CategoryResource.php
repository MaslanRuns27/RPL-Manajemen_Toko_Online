<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CategoryResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CategoryResource\RelationManagers;


class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationGroup = 'Data Buah';
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationLabel = 'Kategori';
    public static ?string $label = 'Kategori';
    protected static ?string $slug = 'Kategori';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                 TextInput::make('name')->required()->label('Nama Kategori'),

            Repeater::make('fruits')
                ->relationship()
                ->schema([
                    FileUpload::make('image')->image()->directory('fruits')->label('Gambar')->columnspan(2),
                    TextInput::make('name')->required()->label('Nama Buah'),
                    TextInput::make('price')->numeric()->required()->label('Harga'),
                    TextInput::make('stock')->numeric()->required()->label('Stok'),
                ])
                ->label('Daftar Buah')
                ->columnSpan('full')
               // ->createItemButtonLabel('Tambah Buah')
                ->columns(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->label('No'),
                TextColumn::make('name')->searchable()->sortable()->label('Nama Kategori'),
                TextColumn::make('created_at')->dateTime()
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()
                    ->color('primary'),
                    Tables\Actions\DeleteAction::make(),
                ])
                // Array of actions
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
