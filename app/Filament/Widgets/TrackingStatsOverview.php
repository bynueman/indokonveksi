<?php

namespace App\Filament\Widgets;

use App\Models\Tracking;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class TrackingStatsOverview extends StatsOverviewWidget
{
    // Hanya bisa dilihat oleh user dengan role 'produksi'
    public static function canView(): bool
    {
        return auth()->user()?->role === 'produksi';
    }

    protected function getCards(): array
    {
        // Hitung jumlah tracking berdasarkan status
        $trackingSelesai = Tracking::where('status', 'selesai')->count();
        $trackingBelumSelesai = Tracking::where('status', '!=', 'selesai')->count();

        return [
            Card::make('Tracking Belum Selesai', $trackingBelumSelesai)
                ->description('Sedang diproses')
                ->descriptionColor('danger')
                ->color('danger'),

            Card::make('Tracking Selesai', $trackingSelesai)
                ->description('Sudah Selesai ✅')
                ->descriptionColor('success')
                ->color('success'),
        ];
    }

    public static function getDefaultColumnSpan(): int
    {
        return 6;
    }
}
