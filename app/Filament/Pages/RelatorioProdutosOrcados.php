<?php

namespace App\Filament\Pages;

use Filament\Tables;
use Filament\Pages\Page;
use App\Models\ItemOrcamento;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
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
                    ->getStateUsing(function ($record) {
                        // Calcula o total considerando o desconto
                        $precoTotalSemDesconto = $record->quantidade * $record->preco_unitario;

                        if ($record->desconto > 0) {
                            // Aplica o desconto percentual
                            $descontoValor = ($record->desconto / 100) * $precoTotalSemDesconto;
                            return $precoTotalSemDesconto - $descontoValor;
                        }

                        // Sem desconto → preço cheio
                        return $precoTotalSemDesconto;
                    })
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

        $total = $query->get()->sum(function ($item) {
            $precoTotalSemDesconto = $item->quantidade * $item->preco_unitario;

            if ($item->desconto > 0) {
                // Aplica o desconto percentual
                $descontoValor = ($item->desconto / 100) * $precoTotalSemDesconto;
                return $precoTotalSemDesconto - $descontoValor;
            }

            // Sem desconto → preço cheio
            return $precoTotalSemDesconto;
        });

        return 'R$ ' . number_format($total, 2, ',', '.');
    }

    /**
     * Define se o recurso pode ser visualizado na listagem geral do painel.
     * 
     * @return bool
     */
    public static function canViewAny(): bool
    {
        return Auth::user()?->permission === 'gestor';
    }

    /**
     * Define se o recurso deve aparecer no menu de navegação do painel.
     * 
     * @return bool
     */
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()?->permission === 'gestor';
    }
}
