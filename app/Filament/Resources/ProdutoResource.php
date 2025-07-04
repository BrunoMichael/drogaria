<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Produto;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\ProdutoResource\Pages;

/**
 * Classe Resource para gerenciar o CRUD de Produtos no painel Filament.
 */
class ProdutoResource extends Resource
{
    /**
     * Define o model associado a este resource.
     *
     * @var class-string<Produto>
     */
    protected static ?string $model = Produto::class;

    /**
     * Ícone do menu de navegação do Filament.
     *
     * @var string|null
     */
    protected static ?string $navigationIcon = 'heroicon-o-cube';

    /**
     * Grupo de navegação no painel administrativo.
     *
     * @var string|null
     */
    protected static ?string $navigationGroup = 'Cadastros';

    /**
     * Posição do recurso no menu de navegação.
     *
     * @var int|null
     */
    protected static ?int $navigationSort = 1;

    /**
     * Define os campos do formulário de criação/edição de produto.
     *
     * @param Form $form
     * @return Form
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('descricao')
                    ->label('Descrição')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('preco')
                    ->label('Preço')
                    ->required()
                    ->numeric()
                    ->step(0.01),
            ]);
    }

    /**
     * Define as colunas da tabela de listagem de produtos.
     *
     * @param Table $table
     * @return Table
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('codigo')
                    ->label('Código')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('descricao')
                    ->label('Descrição')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('preco')
                    ->label('Preço')
                    ->sortable()
                    ->money('BRL'),

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
                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => Auth::user()?->permission === 'gestor'),
            ])
            ->bulkActions([
                // Configurar ação em massa
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
            'index' => Pages\ListProdutos::route('/'),
            'create' => Pages\CreateProduto::route('/create'),
            'edit' => Pages\EditProduto::route('/{record}/edit'),
        ];
    }

    /**
     * Define se o recurso pode ser visualizado na listagem geral do painel.
     * 
     * @return bool
     */
    public static function canViewAny(): bool
    {
        return in_array(Auth::user()?->permission, ['gestor', 'gerente']);
    }

    /**
     * Define se o recurso deve aparecer no menu de navegação do painel.
     * 
     * @return bool
     */
    public static function shouldRegisterNavigation(): bool
    {
        return in_array(Auth::user()?->permission, ['gestor', 'gerente']);
    }
}
