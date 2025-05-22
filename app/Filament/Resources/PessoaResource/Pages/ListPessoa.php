<?php

namespace App\Filament\Resources\PessoaResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\PessoaResource;

/**
 * Página responsável pela listagem dos registros de Pessoa.
 */
class ListPessoas extends ListRecords
{
    /**
     * Define o resource associado a esta página de listagem.
     *
     * @var class-string<PessoaResource>
     */
    protected static string $resource = PessoaResource::class;

    /**
     * Define as ações disponíveis no cabeçalho da página de listagem.
     *
     * @return array<int, \Filament\Actions\Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    /**
     * Define se a página pode ser acessada pelo usuário autenticado.
     * 
     * Neste caso, apenas usuários com permissão ou permissões podem acessar
     * a página associada a este recurso.
     *
     * @param array $parameters Parâmetros da rota, se houver.
     * @return bool
     */
    public static function canAccess(array $parameters = []): bool
    {
        return in_array(Auth::user()?->permission, ['gestor', 'gerente', 'vendedor']);
    }
}
