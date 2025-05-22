<?php

namespace App\Filament\Resources\ProdutoResource\Pages;

use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ProdutoResource;

/**
 * Página responsável pela criação de novos registros de Produto.
 *
 * Esta classe estende CreateRecord e define o resource associado.
 */
class CreateProduto extends CreateRecord
{
    /**
     * Define o resource associado a esta página de criação.
     *
     * @var class-string<ProdutoResource>
     */
    protected static string $resource = ProdutoResource::class;

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
        return in_array(Auth::user()?->permission, ['gestor', 'gerente']);
    }
}
