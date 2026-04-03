<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProdukResource\Pages;
use App\Models\Produk;
use Filament\Forms;
use Filament\Forms\Form as FilamentForm;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table as FilamentTable;

class ProdukResource extends Resource
{
    protected static ?string $model = Produk::class;
    protected static ?string $modelLabel = 'Produk';
    protected static ?string $slug = 'produk';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Produk';
    protected static ?int $navigationSort = 1;

    public static function getPluralModelLabel(): string
    {
        return 'Produk';
    }

    public static function form(FilamentForm $form): FilamentForm
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id_produk')
                    ->label('ID Produk')
                    ->disabled()
                    ->dehydrated(false)
                    ->default(fn ($record) => $record?->id_produk ?? 'Auto-generated'),

                Forms\Components\TextInput::make('nama_produk')
                    ->label('Nama Produk')
                    ->required()
                    ->maxLength(100),

                Forms\Components\TextInput::make('harga')
                    ->label('Harga Jual')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('biaya_produksi')
                ->label('Biaya Produksi')
                ->numeric()
                ->required(), // <-- pastikan tidak ada dehydrated(false)

                Forms\Components\TextInput::make('harga_bahan_baku')
                ->label('Harga Bahan Baku')
                ->numeric()
                ->required(),

            ]);
    }

    public static function table(FilamentTable $table): FilamentTable
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_produk')->label('ID Produk')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('nama_produk')->label('Nama Produk')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('harga')->label('Harga Jual')->money('IDR', true)->sortable(),
                Tables\Columns\TextColumn::make('biaya_produksi')->label('Biaya Produksi')->money('IDR', true)->sortable(),
                Tables\Columns\TextColumn::make('harga_bahan_baku')->label('Bahan Baku')->money('IDR', true)->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->color('primary'),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    // Akses hanya untuk owner
    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->role === 'owner';
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->role === 'owner';
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->role === 'owner';
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->role === 'owner';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProduks::route('/'),
            'create' => Pages\CreateProduk::route('/create'),
            'edit' => Pages\EditProduk::route('/{record}/edit'),
        ];
    }
}
