<?php
namespace App\Filament\Widgets;

use Filament\Forms;
use Filament\Widgets\Widget;

class DashboardFilter extends Widget
{
    protected static string $view = 'filament.widgets.dashboard-filter';

    protected int | string | array $columnSpan = 'full';
}
