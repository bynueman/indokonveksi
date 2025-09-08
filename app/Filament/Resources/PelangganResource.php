<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PelangganResource\Pages;
use App\Models\Pelanggan;
use Filament\Forms;
use Filament\Forms\Form as FilamentForm;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table as FilamentTable;

class PelangganResource extends Resource
{
    protected static ?string $model = Pelanggan::class;
    protected static ?string $modelLabel = 'Pelanggan';
    protected static ?string $pluralModelLabel = 'Pelanggan';
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Pelanggan';
    protected static ?string $slug = 'pelanggan';
    protected static ?int $navigationSort =3;//Urutan di menu navigasi

    public static function getPluralModelLabel(): string
    {
        return 'Pelanggan'; // label plural yang muncul di beberapa tempat
    }

    public static function form(FilamentForm $form): FilamentForm
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id_pelanggan')
                    ->label('ID Pelanggan')
                    ->disabled()
                    ->dehydrated(false)
                    ->default(fn ($record) => $record?->id_pelanggan ?? 'Auto-generated'),

                Forms\Components\TextInput::make('nama')
                    ->label('Nama')
                    ->required()
                    ->maxLength(100),

                Forms\Components\TextInput::make('no_hp')
                    ->label('No HP')
                    ->required()
                    ->maxLength(20),

                Forms\Components\Textarea::make('alamat')
                    ->label('Alamat')
                    ->required(),
            ]);
    }

    public static function table(FilamentTable $table): FilamentTable
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_pelanggan')
                    ->label('ID Pelanggan')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('no_hp')
                    ->label('No HP')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('alamat')
                    ->label('Alamat')
                    ->limit(50),
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

public static function shouldRegisterNavigation(): bool
{
    return auth()->user()?->role === 'admin';
}

public static function canViewAny(): bool
{
    return auth()->user()?->role === 'admin';
}

public static function canCreate(): bool
{
    return auth()->user()?->role === 'admin';
}

public static function canEdit($record): bool
{
    return auth()->user()?->role === 'admin';
}

public static function canDelete($record): bool
{
    return auth()->user()?->role === 'admin';
}


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPelanggans::route('/'),
            'create' => Pages\CreatePelanggan::route('/create'),
            'edit' => Pages\EditPelanggan::route('/{record}/edit'),
        ];
    }
}
