<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrcamentoResource\Pages;
use App\Filament\Resources\OrcamentoResource\RelationManagers\ItensRelationManager;
use App\Models\Orcamento;
use App\Models\Pessoa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;

/**
 * Classe Resource para gerenciar o CRUD de Orçamentos no painel Filament.
 */
class OrcamentoResource extends Resource
{
    /**
     * Define o model associado a este resource.
     *
     * @var class-string<Orcamento>
     */
    protected static ?string $model = Orcamento::class;

    /**
     * Ícone do menu de navegação do Filament.
     *
     * @var string|null
     */
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    /**
     * Grupo de navegação no painel administrativo.
     *
     * @var string|null
     */
    protected static ?string $navigationGroup = 'Orçamentos';

    /**
     * Posição do recurso no menu de navegação.
     *
     * @var int|null
     */
    protected static ?int $navigationSort = 2;

    /**
     * Define os campos do formulário de criação/edição de orçamento.
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
                    ->required()
                    ->searchable()
                    ->preload()
                    ->options(fn() => Pessoa::where('eh_vendedor', true)->pluck('nome', 'id'))
                    ->getSearchResultsUsing(function (string $search) {
                        return Pessoa::where('eh_vendedor', true)
                            ->where(function ($query) use ($search) {
                                $query->where('nome', 'like', "%{$search}%")
                                    ->orWhere('codigo', 'like', "%{$search}%");
                            })
                            ->pluck('nome', 'id');
                    }),

                Forms\Components\Select::make('cliente_id')
                    ->label('Cliente')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->options(fn() => Pessoa::where('eh_cliente', true)->pluck('nome', 'id'))
                    ->createOptionForm([
                        Forms\Components\TextInput::make('nome')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->createOptionUsing(function (array $data) {
                        $cliente = Pessoa::create([
                            'nome' => $data['nome'],
                            'eh_cliente' => true,
                            'eh_vendedor' => false,
                        ]);

                        Notification::make()
                            ->title('Cliente criado com sucesso!')
                            ->success()
                            ->send();

                        return $cliente->id;
                    })
                    ->getSearchResultsUsing(function (string $search) {
                        return Pessoa::where('eh_cliente', true)
                            ->where(function ($query) use ($search) {
                                $query->where('nome', 'like', "%{$search}%")
                                    ->orWhere('codigo', 'like', "%{$search}%");
                            })
                            ->pluck('nome', 'id');
                    })
                    ->rule(function (Forms\Get $get) {
                        return function (string $attribute, $value, \Closure $fail) use ($get) {
                            $vendedor = Pessoa::where('id', $get('vendedor_id'))->first();
                            $cliente = Pessoa::where('id', $value)->first();

                            $vendedorNome = $vendedor?->nome ?? '';
                            $clienteNome = $cliente?->nome ?? '';

                            if (strtolower(trim($vendedorNome)) === strtolower(trim($clienteNome))) {
                                $fail('O Vendedor pode ser Cliente, mas não pode ser Cliente e Vendedor ao mesmo tempo.');
                            }
                        };
                    }),
            ]);
    }

    /**
     * Define as colunas da tabela de listagem de orçamentos.
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
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'finalizado' => 'success',
                        'cancelado' => 'danger',
                        'rascunho' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state) => ucfirst($state)),
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
     * Define os relation managers associados ao recurso.
     *
     * @return array<int, class-string>
     */
    public static function getRelations(): array
    {
        return [
            ItensRelationManager::class,
        ];
    }

    /**
     * Define as páginas de CRUD disponíveis para o recurso.
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
