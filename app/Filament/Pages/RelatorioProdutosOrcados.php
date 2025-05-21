<?php

namespace App\Filament\Pages;

use Filament\Tables;
use Filament\Pages\Page;
use App\Models\ItemOrcamento;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;

class RelatorioProdutosOrcados extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Administração';
    protected static ?int $navigationSort = 3;
    protected static ?string $title = 'Relatório';
    protected static string $view = 'filament.pages.relatorio-produtos-orcados';

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query(
                ItemOrcamento::query()
                    ->with(['orcamento.cliente', 'produto'])
            )
            ->columns([
                TextColumn::make('orcamento.id')->label('Orçamento'),
                TextColumn::make('orcamento.cliente.nome')->label('Cliente'),
                TextColumn::make('produto.descricao')->label('Produto'),
                TextColumn::make('quantidade'),
                TextColumn::make('preco_unitario')->money('BRL'),
                TextColumn::make('total')
                    ->label('Total')
                    ->getStateUsing(fn($record) => $record->quantidade * $record->preco_unitario)
                    ->money('BRL')
                    ->alignRight(),
            ])
            ->filters([
                Filter::make('data')
                    ->form([
                        TextInput::make('data_inicial')->label('Data inicial')->type('date'),
                        TextInput::make('data_final')->label('Data final')->type('date'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query->whereHas('orcamento', function ($q) use ($data) {
                            if (!empty($data['data_inicial'])) {
                                $q->whereDate('created_at', '>=', $data['data_inicial']);
                            }
                            if (!empty($data['data_final'])) {
                                $q->whereDate('created_at', '<=', $data['data_final']);
                            }
                        });
                    }),

                Filter::make('status')
                    ->form([
                        Select::make('status')
                            ->multiple()
                            ->options([
                                'rascunho' => 'Rascunho',
                                'finalizado' => 'Finalizado',
                                'cancelado' => 'Cancelado',
                            ])
                            ->default(['rascunho', 'finalizado', 'cancelado']),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['status'])) {
                            $query->whereHas('orcamento', fn($q) => $q->whereIn('status', $data['status']));
                        }
                        return $query;
                    }),

                Filter::make('produto')
                    ->form([
                        TextInput::make('produto')->label('Produto (Codigo ou nome)'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['produto'])) {
                            $query->whereHas('produto', fn($q) =>
                            $q->where('descricao', 'like', "%{$data['produto']}%")
                                ->orWhere('codigo', $data['produto']));
                        }
                        return $query;
                    }),
            ])
            ->defaultSort('orcamento_id', 'desc');
    }

    public function getTotalGeral(): string
    {
        $query = $this->getFilteredTableQuery();

        $total = $query->sum(DB::raw('quantidade * preco_unitario'));

        return 'R$ ' . number_format($total, 2, ',', '.');
    }
}
