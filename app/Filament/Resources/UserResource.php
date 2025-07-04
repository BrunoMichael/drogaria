<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Pessoa;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Filament\Resources\UserResource\Pages;

/**
 * Classe Resource para gerenciar o CRUD de Usuários no painel Filament.
 */
class UserResource extends Resource
{
    /**
     * Define o model associado a este resource.
     *
     * @var class-string<User>
     */
    protected static ?string $model = User::class;

    /**
     * Ícone do menu de navegação do Filament.
     *
     * @var string|null
     */
    protected static ?string $navigationIcon = 'heroicon-o-user';

    /**
     * Grupo de navegação no painel administrativo.
     *
     * @var string|null
     */
    protected static ?string $navigationGroup = 'Administração';

    /**
     * Posição do recurso no menu de navegação.
     *
     * @var int|null
     */
    protected static ?int $navigationSort = 3;

    /**
     * Define os campos do formulário de criação/edição de usuário.
     *
     * @param Form $form
     * @return Form
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('pessoa_id')
                    ->label('Nome')
                    ->relationship('pessoa', 'nome')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->rules(['exists:pessoas,id']),

                Forms\Components\TextInput::make('email')
                    ->label('E-mail')
                    ->required()
                    ->email()
                    ->unique(ignoreRecord: true),

                Forms\Components\TextInput::make('password')
                    ->label('Senha')
                    ->password()
                    ->required(fn(string $context) => $context === 'create')
                    ->dehydrated(fn($state) => filled($state))
                    ->dehydrateStateUsing(fn($state) => Hash::make($state))
                    ->maxLength(255),

                Forms\Components\Select::make('permission')
                    ->label('Permissão')
                    ->options([
                        'gestor' => 'Gestor',
                        'gerente' => 'Gerente',
                        'vendedor' => 'Vendedor',
                    ])
                    ->required(),
            ]);
    }

    /**
     * Define as colunas da tabela de listagem de usuários.
     *
     * @param Table $table
     * @return Table
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('pessoa.nome')->label('Nome')->searchable(),
                Tables\Columns\TextColumn::make('email')->label('E-mail')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Criado em')->dateTime()->sortable(),
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
     * Define as páginas disponíveis para o recurso.
     *
     * @return array<string, \Filament\Resources\Pages\Page>
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
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
