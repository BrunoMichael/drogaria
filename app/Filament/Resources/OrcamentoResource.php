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


/**
 * Class OrcamentoResource
 *
 * Recurso do Filament para gerenciar registros de orçamentos.
 */
class OrcamentoResource extends Resource
{
    protected static ?string $model = Orcamento::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Orçamentos';
    protected static ?int $navigationSort = 3;

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
                    ->searchable()
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

    public static function getRelations(): array
    {
        return [
            ItensRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrcamentos::route('/'),
            'create' => Pages\CreateOrcamento::route('/create'),
            'edit' => Pages\EditOrcamento::route('/{record}/edit'),
        ];
    }
}
