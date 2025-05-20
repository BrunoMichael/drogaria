<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OfertaResource\Pages;
use App\Models\Oferta;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * Resource OfertaResource
 *
 * Responsável pela interface de gerenciamento de ofertas promocionais no painel administrativo.
 */
class OfertaResource extends Resource
{
    /**
     * Modelo principal vinculado ao resource.
     *
     * @var class-string<\App\Models\Oferta>
     */
    protected static ?string $model = Oferta::class;

    /**
     * Ícone exibido na navegação do painel.
     */
    protected static ?string $navigationIcon = 'heroicon-o-tag';

    /**
     * Grupo de navegação no painel.
     */
    protected static ?string $navigationGroup = 'Promoções';

    /**
     * Define o formulário de criação/edição de ofertas.
     *
     * @param Form $form
     * @return Form
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('produto_id')
                    ->label('Produto')
                    ->relationship('produto', 'descricao')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\TextInput::make('quantidade_levar')
                    ->label('Quantidade Levar')
                    ->required()
                    ->numeric()
                    ->integer()
                    ->gt('quantidade_pagar'),

                Forms\Components\TextInput::make('quantidade_pagar')
                    ->label('Quantidade Pagar')
                    ->required()
                    ->numeric()
                    ->integer()
                    ->lt('quantidade_levar'),
            ]);
    }

    /**
     * Define a tabela de listagem das ofertas.
     *
     * @param Table $table
     * @return Table
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('produto.descricao')
                    ->label('Produto')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('quantidade_levar')
                    ->label('Levar'),

                Tables\Columns\TextColumn::make('quantidade_pagar')
                    ->label('Pagar'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filtros customizados podem ser adicionados aqui
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    /**
     * Define os relation managers disponíveis.
     *
     * @return array
     */
    public static function getRelations(): array
    {
        return [
            // RelationManagers podem ser adicionados aqui
        ];
    }

    /**
     * Define as páginas disponíveis para o recurso.
     *
     * @return array<string, \Filament\Resources\Pages\Page>
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOfertas::route('/'),
            'create' => Pages\CreateOferta::route('/create'),
            'edit' => Pages\EditOferta::route('/{record}/edit'),
        ];
    }
}
