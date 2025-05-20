<?php

namespace App\Filament\Resources\OrcamentoResource\RelationManagers;

use App\Models\Oferta;
use App\Models\Produto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * Gerenciador de relacionamento para os itens do orçamento.
 * 
 * Essa classe gerencia a interface e lógica para adicionar, editar e listar
 * os itens relacionados a um orçamento no painel Filament.
 */
class ItensRelationManager extends RelationManager
{
    /**
     * Nome do relacionamento Eloquent que esta classe gerencia.
     *
     * @var string
     */
    protected static string $relationship = 'itens';

    /**
     * Define o formulário para criação e edição dos itens do orçamento.
     *
     * Inclui campos para seleção do produto, quantidade, preço unitário e desconto.
     * Aplica lógica para atualizar preço e desconto baseados nas ofertas vigentes.
     *
     * @param Form $form
     * @return Form
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('produto_id')
                    ->label('Produto')
                    ->relationship('produto', 'descricao')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (Forms\Set $set, $state) {
                        $produto = Produto::find($state);
                        $set('preco_unitario', $produto?->preco ?? 0);
                        $set('quantidade', 1);
                        $set('desconto', 0);
                    })
                    ->createOptionForm([
                        Forms\Components\TextInput::make('codigo')
                            ->label('Código')
                            ->required()
                            ->unique()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('descricao')
                            ->label('Descrição')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('preco')
                            ->label('Preço')
                            ->required()
                            ->numeric()
                            ->step(0.01),
                    ]),
                Forms\Components\TextInput::make('quantidade')
                    ->label('Quantidade')
                    ->required()
                    ->numeric()
                    ->integer()
                    ->minValue(1)
                    ->reactive()
                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                        $produtoId = $get('produto_id');
                        $quantidade = $get('quantidade');
                        $oferta = Oferta::where('produto_id', $produtoId)
                            ->where('quantidade_levar', '<=', $quantidade)
                            ->orderBy('quantidade_levar', 'desc')
                            ->first();

                        if ($oferta) {
                            // Calcula quantidade paga com base na oferta "Leve X, Pague Y"
                            $quantidadePaga = floor($quantidade / $oferta->quantidade_levar) * $oferta->quantidade_pagar + ($quantidade % $oferta->quantidade_levar);
                            $precoUnitarioComDesconto = ($oferta->quantidade_pagar / $oferta->quantidade_levar) * $get('preco_unitario');
                            $set('desconto', round((1 - ($precoUnitarioComDesconto / $get('preco_unitario'))) * 100, 2));
                        } else {
                            $set('desconto', 0);
                        }
                    }),
                Forms\Components\TextInput::make('preco_unitario')
                    ->label('Preço Unitário')
                    ->required()
                    ->numeric()
                    ->step(0.01)
                    ->readOnly(),
                Forms\Components\TextInput::make('desconto')
                    ->label('Desconto (%)')
                    ->numeric()
                    ->step(0.01)
                    ->readOnly(),
            ]);
    }

    /**
     * Configura a tabela que lista os itens do orçamento.
     * 
     * Define colunas, ações e filtros disponíveis.
     *
     * @param Table $table
     * @return Table
     */
    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('produto.codigo')->label('Código Produto'),
                Tables\Columns\TextColumn::make('produto.descricao')->label('Descrição Produto'),
                Tables\Columns\TextColumn::make('quantidade'),
                Tables\Columns\TextColumn::make('preco_unitario')->money('BRL'),
                Tables\Columns\TextColumn::make('desconto')->formatStateUsing(fn($state) => $state . '%'),
                Tables\Columns\TextColumn::make('preco_total')->getStateUsing(function ($record) {
                    return ($record->quantidade * $record->preco_unitario) * (1 - ($record->desconto / 100));
                })->money('BRL'),
            ])
            ->filters([
                // Defina filtros personalizados, se necessário
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    /**
     * Modifica a query padrão para incluir o relacionamento 'produto' ao buscar os itens.
     *
     * Isso melhora a performance evitando consultas N+1.
     *
     * @return Builder
     */
    protected function getQuery(): Builder
    {
        return parent::getQuery()->with('produto');
    }
}
