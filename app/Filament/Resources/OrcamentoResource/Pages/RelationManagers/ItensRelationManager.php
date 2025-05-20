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
 * Relation Manager responsável pelos itens de um orçamento.
 * Garante o vínculo entre orçamento e produtos selecionados,
 * incluindo aplicação automática de ofertas promocionais.
 */
class ItensRelationManager extends RelationManager
{
    // Define o nome do relacionamento (relacionado ao modelo pai)
    protected static string $relationship = 'itens';

    /**
     * Define o formulário usado para adicionar/editar itens no orçamento.
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
                        // Aplica a lógica de promoção após mudança na quantidade
                        $produtoId = $get('produto_id');
                        $quantidade = $get('quantidade');
                        $precoUnitario = $get('preco_unitario');

                        // Busca a melhor oferta aplicável (maior quantidade_levar possível)
                        $oferta = Oferta::where('produto_id', $produtoId)
                            ->where('quantidade_levar', '<=', $quantidade)
                            ->orderBy('quantidade_levar', 'desc')
                            ->first();

                        if ($oferta) {
                            // Calcula a quantidade paga aplicando a promoção
                            $grupos = floor($quantidade / $oferta->quantidade_levar);
                            $sobra = $quantidade % $oferta->quantidade_levar;

                            $quantidadePaga = $grupos * $oferta->quantidade_pagar + $sobra;

                            // Calcula desconto percentual aplicado
                            $totalSemDesconto = $quantidade * $precoUnitario;
                            $totalComDesconto = $quantidadePaga * $precoUnitario;

                            $descontoPercentual = round((1 - ($totalComDesconto / $totalSemDesconto)) * 100, 2);

                            $set('desconto', $descontoPercentual);
                        } else {
                            // Nenhuma oferta aplicável → sem desconto
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
     * Define a tabela exibida na interface, com colunas de itens do orçamento.
     */
    public function table(Table $table): Table
    {
        return $table
            ->columns([
                // Código e descrição do produto
                Tables\Columns\TextColumn::make('produto.codigo')->label('Código Produto'),
                Tables\Columns\TextColumn::make('produto.descricao')->label('Descrição Produto'),

                // Quantidade adquirida
                Tables\Columns\TextColumn::make('quantidade'),

                // Preço unitário formatado
                Tables\Columns\TextColumn::make('preco_unitario')->label('Preço Unitário')->money('BRL'),

                // Desconto aplicado formatado
                Tables\Columns\TextColumn::make('desconto')
                    ->label('Desconto')
                    ->formatStateUsing(fn($state) => $state . '%'),

                // Preço total com aplicação de promoções
                Tables\Columns\TextColumn::make('preco_total')
                    ->label('Preço Total')
                    ->getStateUsing(function ($record) {
                        // Busca a melhor oferta aplicável
                        $oferta = $record->produto->ofertas()
                            ->where('quantidade_levar', '<=', $record->quantidade)
                            ->orderBy('quantidade_levar', 'desc')
                            ->first();

                        if ($oferta) {
                            // Aplica a promoção para calcular valor final
                            $grupos = floor($record->quantidade / $oferta->quantidade_levar);
                            $sobra = $record->quantidade % $oferta->quantidade_levar;

                            $quantidadePaga = $grupos * $oferta->quantidade_pagar + $sobra;

                            return $quantidadePaga * $record->preco_unitario;
                        }

                        // Sem promoção → preço cheio
                        return $record->quantidade * $record->preco_unitario;
                    })
                    ->money('BRL'),
            ])
            ->filters([
                // Filtros adicionais, se necessário
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
     * Garante o carregamento do relacionamento com o modelo Produto
     * para evitar consultas N+1 na tabela.
     */
    protected function getQuery(): Builder
    {
        return parent::getQuery()->with('produto');
    }
}
