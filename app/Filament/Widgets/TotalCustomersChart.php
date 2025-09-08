<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Pelanggan;
use Illuminate\Support\Carbon;

class TotalCustomersChart extends ChartWidget
{
    protected static ?string $heading = 'Konsumen per Bulan';
    protected static string $color = 'warning';

    public static function canView(): bool
    {
        return in_array(auth()->user()->role, ['admin', 'owner']);
    }

    protected function getData(): array
{
    // Ambil jumlah pelanggan per bulan dalam tahun ini
    $monthly = Pelanggan::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
        ->whereYear('created_at', now()->year)
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('total', 'month');

    // Buat data untuk semua bulan (isi 0 kalau tidak ada data)
    $data = [];
    $labels = [];
    foreach (range(1, 12) as $m) {
        $labels[] = Carbon::create()->month($m)->format('M');
        $data[] = $monthly->get($m, 0); // Ambil jumlah atau 0 jika tidak ada
    }

    return [
        'labels' => $labels,
        'datasets' => [[
            'label' => 'Konsumen',
            'data' => $data,
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

    public function getColumnSpan(): int|string|array
    {
        return 3;
    }
}
