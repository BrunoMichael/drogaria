<?php

namespace App\Filament\Widgets;

use App\Models\Orcamento;
use Carbon\Carbon;
use Filament\Widgets\LineChartWidget;

/**
 * Classe OrcamentosChart
 *
 * Widget de gráfico de linha que exibe a quantidade de orçamentos
 * realizados nos últimos 7 dias, incluindo hoje.
 */
class OrcamentosChart extends LineChartWidget
{
    /**
     * Título exibido no topo do widget.
     *
     * @var string|null
     */
    protected static ?string $heading = 'Orçamentos nos últimos 7 dias';

    /**
     * Gera os dados que serão exibidos no gráfico.
     *
     * @return array Dados formatados para o gráfico de linha,
     *               incluindo labels (datas) e contagem de orçamentos por dia.
     */
    protected function getData(): array
    {
        // Gera um intervalo de 7 dias (6 dias atrás até hoje)
        $data = collect(range(6, 0))->map(function ($daysAgo) {
            // Calcula a data correspondente
            $date = Carbon::today()->subDays($daysAgo)->toDateString();

            // Retorna a data e o total de orçamentos criados nesse dia
            return [
                'date' => $date,
                'count' => Orcamento::whereDate('created_at', $date)->count(),
            ];
        });

        // Retorna os dados no formato esperado pelo gráfico
        return [
            'datasets' => [
                [
                    'label' => 'Orçamentos',
                    'data' => $data->pluck('count'),
                ],
            ],
            'labels' => $data->pluck('date'),
        ];
    }

    /**
     * Define a largura do widget no dashboard.
     *
     * @return int|string|array Pode retornar número de colunas, "full", etc.
     */
    public function getColumnSpan(): int|string|array
    {
        return 'full';
    }
}
