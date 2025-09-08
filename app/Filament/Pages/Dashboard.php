<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\AccountWidget;
use App\Filament\Widgets\{
    DashboardStatsOverview,
    OrdersPerMonthChart,
    TotalCustomersChart,
    TrackingStatsOverview,
    TrackingStatusTable
};

class Dashboard extends BaseDashboard
{
    /**
     * Konfigurasi kolom untuk responsif layout.
     */
    public function getColumns(): int|array
    {
        return [
            'default' => 1,
            'md' => 3,
            'lg' => 6,
        ];
    }

    /**
     * Widget di bagian header, seperti info akun.
     */
    public function getHeaderWidgets(): array
    {
        return [
            AccountWidget::class,
        ];
    }

    /**
     * Widget utama di dashboard.
     */
    public function getWidgets(): array
    {
        return [
            DashboardStatsOverview::class,   // berisi Revenue, New Customers, New Orders
            OrdersPerMonthChart::class,
            TotalCustomersChart::class,
            TrackingStatsOverview::class, // hanya untuk user dengan role 'produksi'
            TrackingStatusTable::class,
        ];
    }
}
