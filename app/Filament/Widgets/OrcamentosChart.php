<?php

namespace App\Filament\Widgets;

use App\Models\Orcamento;
use Carbon\Carbon;
use Filament\Widgets\LineChartWidget;

class OrcamentosChart extends LineChartWidget
{
    protected static ?string $heading = 'Orçamentos nos últimos 7 dias';

    protected function getData(): array
    {
        $data = collect(range(6, 0))->map(function ($daysAgo) {
            $date = Carbon::today()->subDays($daysAgo)->toDateString();
            return [
                'date' => $date,
                'count' => Orcamento::whereDate('created_at', $date)->count(),
            ];
        });

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

    public function getColumnSpan(): int|string|array
    {
        return 'full';
    }
}
