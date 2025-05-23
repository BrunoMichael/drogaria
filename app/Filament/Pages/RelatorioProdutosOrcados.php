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

/**
 * Página personalizada no painel Filament que exibe um relatório
 * detalhado dos produtos orçados, com filtros por data, status e produto.
 *
 * Apenas usuários com permissão "gestor" podem visualizar ou acessar
 * essa página via navegação.
 */
class RelatorioProdutosOrcados extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    /**
     * Ícone que representa a página na navegação lateral.
     *
     * @var string|null
     */
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    /**
     * Agrupamento da página na navegação.
     *
     * @var string|null
     */
    protected static ?string $navigationGroup = 'Administração';

    /**
     * Ordem de exibição no menu de navegação (menor = mais acima).
     *
     * @var int|null
     */
    protected static ?int $navigationSort = 3;

    /**
     * Título da página exibido no topo e na navegação.
     *
     * @var string|null
     */
    protected static ?string $title = 'Relatório';

    /**
     * Caminho da view Blade personalizada associada à página.
     *
     * @var string
     */
    protected static string $view = 'filament.pages.relatorio-produtos-orcados';

    /**
     * Define a tabela de exibição dos dados com colunas, filtros e ordenação.
     *
     * @param Tables\Table $table
     * @return Tables\Table
     */
    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query(
                ItemOrcamento::query()->with(['orcamento.cliente', 'produto'])
            )
            ->columns([
                TextColumn::make('orcamento.id')->label('Orçamento'),
                TextColumn::make('orcamento.cliente.nome')->label('Cliente'),
                TextColumn::make('produto.descricao')->label('Produto'),
                TextColumn::make('quantidade'),
                TextColumn::make('preco_unitario')->money('BRL'),

                // Coluna calculada com base na quantidade, preço unitário e desconto
                TextColumn::make('total')
                    ->label('Total')
                    ->getStateUsing(function ($record) {
                        $precoTotalSemDesconto = $record->quantidade * $record->preco_unitario;

                        if ($record->desconto > 0) {
                            $descontoValor = ($record->desconto / 100) * $precoTotalSemDesconto;
                            return $precoTotalSemDesconto - $descontoValor;
                        }

                        return $precoTotalSemDesconto;
                    })
                    ->money('BRL')
                    ->alignRight(),
            ])
            ->filters([
                // Filtro por intervalo de datas (data de criação do orçamento)
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

                // Filtro por status do orçamento
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
                            $query->whereHas('orcamento', fn($q) =>
                                $q->whereIn('status', $data['status'])
                            );
                        }
                        return $query;
                    }),

                // Filtro por produto (código ou nome parcial)
                Filter::make('produto')
                    ->form([
                        TextInput::make('produto')->label('Produto (Código ou nome)'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['produto'])) {
                            $query->whereHas('produto', fn($q) =>
                                $q->where('descricao', 'like', "%{$data['produto']}%")
                                  ->orWhere('codigo', $data['produto'])
                            );
                        }
                        return $query;
                    }),
            ])
            ->defaultSort('orcamento_id', 'desc');
    }

    /**
     * Calcula o total geral dos itens exibidos na tabela,
     * levando em consideração os filtros aplicados e os descontos.
     *
     * @return string Total formatado em reais.
     */
    public function getTotalGeral(): string
    {
        $query = $this->getFilteredTableQuery();

        $total = $query->get()->sum(function ($item) {
            $precoTotalSemDesconto = $item->quantidade * $item->preco_unitario;

            if ($item->desconto > 0) {
                $descontoValor = ($item->desconto / 100) * $precoTotalSemDesconto;
                return $precoTotalSemDesconto - $descontoValor;
            }

            return $precoTotalSemDesconto;
        });

        return 'R$ ' . number_format($total, 2, ',', '.');
    }

    /**
     * Define se a página pode ser acessada por qualquer usuário autenticado.
     * Neste caso, apenas usuários com permissão "gestor".
     *
     * @return bool
     */
    public static function canViewAny(): bool
    {
        return Auth::user()?->permission === 'gestor';
    }

    /**
     * Define se a página deve ser exibida no menu de navegação lateral.
     * Apenas para usuários com permissão "gestor".
     *
     * @return bool
     */
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()?->permission === 'gestor';
    }
}
