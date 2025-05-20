<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PessoaResource\Pages;
use App\Models\Pessoa;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * Resource PessoaResource
 *
 * Responsável pela interface administrativa do modelo Pessoa no painel do Filament.
 */
class PessoaResource extends Resource
{
    /**
     * Modelo principal relacionado ao recurso.
     *
     * @var class-string<\App\Models\Pessoa>
     */
    protected static ?string $model = Pessoa::class;

    /**
     * Ícone do menu lateral.
     */
    protected static ?string $navigationIcon = 'heroicon-o-user';

    /**
     * Grupo de navegação do menu lateral.
     */
    protected static ?string $navigationGroup = 'Cadastros';
    protected static ?int $navigationSort = 1;


    /**
     * Define o formulário de criação/edição.
     */
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nome')
                ->label('Nome')
                ->required()
                ->maxLength(255)
                ->columnSpan('full'),

            Forms\Components\Grid::make(2)
                ->schema([
                    Forms\Components\Toggle::make('eh_cliente')
                        ->label('É Cliente')
                        ->columnSpan(1),

                    Forms\Components\Toggle::make('eh_vendedor')
                        ->label('É Vendedor')
                        ->columnSpan(1)
                        ->visible(function (callable $get) {
                            $email = $get('email');

                            if (!$email) {
                                return false;
                            }

                            $pessoaExists = \App\Models\Pessoa::where('email', $email)->exists();
                            $userExists = User::where('email', $email)->exists();

                            return $pessoaExists && $userExists;
                        }),
                ]),
        ]);
    }

    /**
     * Define a tabela de listagem de pessoas.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('codigo')
                    ->label('Código')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nome')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('eh_cliente')
                    ->label('Cliente')
                    ->boolean(),

                Tables\Columns\IconColumn::make('eh_vendedor')
                    ->label('Vendedor')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    /**
     * Define os relation managers disponíveis.
     */
    public static function getRelations(): array
    {
        return [];
    }

    /**
     * Define as páginas disponíveis para o recurso.
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPessoas::route('/'),
            'create' => Pages\CreatePessoa::route('/create'),
            'edit' => Pages\EditPessoa::route('/{record}/edit'),
        ];
    }
}
