<?php

namespace App\Filament\Pages;

use App\Models\Orcamento;
use App\Models\Pessoa;
use Carbon\Carbon;
use Filament\Pages\Page;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $view = 'filament.pages.dashboard';
    protected static ?string $title = 'Painel de Controle';

    protected static ?int $navigationSort = -1; // para aparecer no topo
}
