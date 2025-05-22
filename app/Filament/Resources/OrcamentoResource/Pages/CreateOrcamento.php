<?php

namespace App\Filament\Resources\OrcamentoResource\Pages;

use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\OrcamentoResource;

/**
 * Página responsável por criar novos registros de Orçamento.
 *
 * Esta classe estende CreateRecord e define o resource relacionado.
 */
class CreateOrcamento extends CreateRecord
{
    /**
     * Define o resource associado a esta página de criação.
     *
     * @var class-string<OrcamentoResource>
     */
    protected static string $resource = OrcamentoResource::class;

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
