<?php

namespace App\Filament\Resources\PessoaResource\Pages;

use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\PessoaResource;
use Filament\Resources\Pages\CreateRecord;

/**
 * Página responsável por criar novos registros de Pessoa.
 */
class CreatePessoa extends CreateRecord
{
    /**
     * Define o resource associado a esta página de criação.
     *
     * @var class-string<PessoaResource>
     */
    protected static string $resource = PessoaResource::class;

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
