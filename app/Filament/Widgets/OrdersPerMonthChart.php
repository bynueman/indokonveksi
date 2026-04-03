<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Pesanan;
use Illuminate\Support\Carbon;

class OrdersPerMonthChart extends ChartWidget
{
    protected static ?string $heading = 'Pesanan per Bulan';
    protected static string  $color   = 'warning';

    public static function canView(): bool
{
    return in_array(auth()->user()->role, ['admin', 'owner']);
}


    protected function getData(): array
    {
        // hitung total order per bulan di tahun berjalan
        $raw = Pesanan::selectRaw('MONTH(tanggal_pesanan) m, COUNT(*) total')
            ->whereYear('tanggal_pesanan', now()->year)
            ->groupBy('m')->orderBy('m')
            ->pluck('total', 'm');

        $labels = collect(range(1, 12))
            ->map(fn ($m) => Carbon::create()->month($m)->format('M'))
            ->toArray();

        $values = collect(range(1, 12))
            ->map(fn ($m) => $raw->get($m, 0))
            ->toArray();

        return [
            'labels'   => $labels,
            'datasets' => [[
                'label' => 'Pesanan',
                'data'  => $values,
            ]],
        ];
    }

        protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'ticks' => [
                        'precision' => 0, // ini yang penting biar tidak ada 4.0
                        'stepSize' => 1,
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    /** lebar 2 kolom */
public function getColumnSpan(): int|string|array
{
    return 3;
}

}
