<?php

namespace App\Filament\Widgets;

use App\Models\Tracking;
use Filament\Widgets\TableWidget;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TrackingStatusTable extends TableWidget
{
    protected static ?string $heading = 'Tracking Status';
    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
{
    return in_array(auth()->user()->role, ['admin', 'owner', 'produksi']);
}

    public function getTableQuery(): Builder
    {
        return Tracking::with('pesanan')
            ->orderByRaw("CASE WHEN status = 'selesai' THEN 1 ELSE 0 END")
            ->orderByDesc('id_tracking');
    }

    public function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('id_tracking')
                ->label('Tracking ID')
                ->sortable(),

            Tables\Columns\TextColumn::make('pesanan.nama_pesanan')
                ->label('Nama Pesanan')
                ->sortable(),

            Tables\Columns\TextColumn::make('tanggal')
                ->label('Tanggal Masuk')
                ->date()
                ->sortable(),

            Tables\Columns\TextColumn::make('pesanan.deadline')
                ->label('Deadline')
                ->date()
                ->sortable(),

            Tables\Columns\BadgeColumn::make('status')
                ->label('Status')
                ->colors([
                    'success' => fn ($state) => $state === 'selesai',
                    'danger' => fn ($state) => $state !== 'selesai',
                ])
                ->sortable(),

            Tables\Columns\TextColumn::make('pesanan.catatan')
                ->label('Catatan')
                ->wrap()
                ->limit(50),
        ];
    }
}
