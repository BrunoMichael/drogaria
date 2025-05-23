<?php

namespace App\Filament\Widgets;

use App\Models\Pessoa;
use App\Models\Orcamento;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

/**
 * Classe DashboardStats
 *
 * Este widget exibe estatísticas no painel do Filament, incluindo:
 * - Total de clientes cadastrados
 * - Quantidade de orçamentos feitos no dia atual
 * - Quantidade de orçamentos feitos no mês atual
 *
 * É exibido como um componente de "Stats Overview" do Filament.
 */
class DashboardStats extends BaseWidget
{
    /**
     * Retorna os cartões de estatísticas para exibição no dashboard.
     *
     * @return array<Card> Lista de cartões com título e valor correspondente.
     */
    protected function getCards(): array
    {
        return [
            // Card que exibe o total de pessoas cadastradas como clientes
            Card::make('Total de Clientes', Pessoa::where('eh_cliente', true)->count()),

            // Card que exibe a quantidade de orçamentos cadastrados hoje
            Card::make('Orçamentos Hoje', Orcamento::whereDate('created_at', Carbon::today())->count()),

            // Card que exibe a quantidade de orçamentos cadastrados no mês atual
            Card::make('Orçamentos no Mês', Orcamento::whereMonth('created_at', now()->month)->count()),
        ];
    }
}
