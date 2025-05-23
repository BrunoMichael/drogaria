<?php

namespace App\Filament\Widgets;

use App\Models\Pessoa;
use App\Models\Orcamento;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class DashboardStats extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total de Clientes', Pessoa::count()),

            Card::make('Orçamentos Hoje', Orcamento::whereDate('created_at', Carbon::today())->count()),

            Card::make('Orçamentos no Mês', Orcamento::whereMonth('created_at', now()->month)->count()),
        ];
    }
}
