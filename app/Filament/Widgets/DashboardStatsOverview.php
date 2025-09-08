<?php

namespace App\Filament\Widgets;

use App\Models\Pelanggan;
use App\Models\Pesanan;
use App\Models\Invoice;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Carbon;

class DashboardStatsOverview extends StatsOverviewWidget
{

    public static function canView(): bool
{
    return in_array(auth()->user()->role, ['admin', 'owner']);
}

    protected function getCards(): array
    {
        $tanggalBatas = Carbon::now()->subMonth();

        $totalRevenue = Pesanan::join('invoice', 'invoice.pesanan_id', '=', 'pesanan.id_pesanan')
    ->where('invoice.tanggal_invoice', '>=', now()->subMonth())
    ->sum('invoice.jumlah_dibayar');



$totalKurangBayar = Pesanan::join('invoice', 'invoice.pesanan_id', '=', 'pesanan.id_pesanan')
    ->where('invoice.tanggal_invoice', '>=', now()->subMonth())
    ->get()
    ->sum(fn ($inv) => $inv->total_tagihan - $inv->jumlah_dibayar);


        // Format tampilan
        $formattedRevenue = number_format($totalRevenue, 0, ',', '.');
        $formattedKurang = number_format($totalKurangBayar, 0, ',', '.');

        // New Customers & Orders (30 hari terakhir)
        $newCustomers = Pelanggan::where('created_at', '>=', $tanggalBatas)->count();
        $newOrders = Pesanan::where('tanggal_pesanan', '>=', $tanggalBatas)->count();


        return [
            Card::make('Total Omset', "Rp {$formattedRevenue}")
                ->description('Total sudah dibayar 💰')
                ->descriptionColor('success')
                ->color('success'),

            Card::make('Kurang Bayar', "Rp {$formattedKurang}")
                ->description('Total belum dibayar 💸')
                ->descriptionColor('danger')
                ->color('danger'),

            Card::make('New Customers', $newCustomers)
                ->description('30-day sign-ups')
                ->descriptionIcon('heroicon-m-user')
                ->color('success'),

            Card::make('New Orders', $newOrders)
                ->description('30-day orders')
                ->descriptionIcon('heroicon-m-clipboard-document')
                ->color('success'),
        ];
    }

    public static function getDefaultColumnSpan(): int
    {
        return 6;
    }
}
