<?php

namespace App\Filament\Pages;

use App\Models\Orcamento;
use App\Models\Pessoa;
use Carbon\Carbon;
use Filament\Pages\Page;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

/**
 * Classe Dashboard
 *
 * Representa a página principal do painel administrativo (dashboard)
 * no sistema Filament. Pode exibir widgets como gráficos, contadores,
 * e estatísticas de orçamentos, clientes, etc.
 */
class Dashboard extends Page
{
    /**
     * Ícone exibido no menu de navegação ao lado do nome da página.
     *
     * @var string|null
     */
    protected static ?string $navigationIcon = 'heroicon-o-home';

    /**
     * Caminho da view Blade que renderiza o conteúdo do dashboard.
     *
     * @var string
     */
    protected static string $view = 'filament.pages.dashboard';

    /**
     * Título da página exibido no cabeçalho e na navegação.
     *
     * @var string|null
     */
    protected static ?string $title = 'Painel de Controle';

    /**
     * Define a ordem de exibição da página no menu de navegação.
     * Um valor menor coloca a página mais acima no menu.
     *
     * @var int|null
     */
    protected static ?int $navigationSort = -1;
}
