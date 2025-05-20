<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrcamentoResource\Pages;
use App\Filament\Resources\OrcamentoResource\RelationManagers\ItensRelationManager;
use App\Models\Cliente;
use App\Models\Orcamento;
use App\Models\Vendedor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * Class OrcamentoResource
 *
 * Recurso do Filament para gerenciar registros de orçamentos.
 *
 * @package App\Filament\Resources
 */
class OrcamentoResource extends Resource
{
    /**
     * Modelo associado ao recurso.
     *
     * @var string|null
     */
    protected static ?string $model = Orcamento::class;

    /**
     * Ícone de navegação no painel.
     *
     * @var string|null
     */
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    /**
     * Grupo de navegação onde o recurso será exibido.
     *
     * @var string|null
     */
    protected static ?string $navigationGroup = 'Orçamentos';

    /**
     * Define o formulário de criação/edição do recurso.
     *
     * @param Form $form
     * @return Form
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('vendedor_id')
                    ->label('Vendedor')
                    ->relationship('vendedor', 'nome')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->options(fn () => Vendedor::all()->pluck('nome', 'id'))
                    ->createOptionForm([
                        Forms\Components\TextInput::make('codigo')
                            ->label('Código')
                            ->required()
                            ->unique()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('nome')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255),
                    ]),
                Forms\Components\Select::make('cliente_id')
                    ->label('Cliente')
                    ->relationship('cliente', 'nome')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->options(fn () => Cliente::all()->pluck('nome', 'id'))
                    ->createOptionForm([
                        Forms\Components\TextInput::make('codigo')
                            ->label('Código')
                            ->required()
                            ->unique()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('nome')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->rule(function (Forms\Get $get) {
                        return function (string $attribute, $value, \Closure $fail) use ($get) {
                            $vendedorId = $get('vendedor_id');
                            $vendedorNomeOriginal = \App\Models\Vendedor::find($vendedorId)?->nome ?? '';
                            $clienteNomeOriginal = \App\Models\Cliente::find($value)?->nome ?? '';

                            $vendedorNome = strtolower(preg_replace('/\s+/', ' ', $vendedorNomeOriginal));
                            $clienteNome = strtolower(preg_replace('/\s+/', ' ', $clienteNomeOriginal));

                            if ($clienteNome === $vendedorNome) {
                                $fail('O Vendedor não pode ser o mesmo que o Cliente.');
                            }
                        };
                    }),
            ]);
    }

    /**
     * Define a tabela de listagem do recurso.
     *
     * @param Table $table
     * @return Table
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('vendedor.nome')->label('Vendedor')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('cliente.nome')->label('Cliente')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
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
            ItensRelationManager::class,
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
            'index' => Pages\ListOrcamentos::route('/'),
            'create' => Pages\CreateOrcamento::route('/create'),
            'edit' => Pages\EditOrcamento::route('/{record}/edit'),
        ];
    }
}
