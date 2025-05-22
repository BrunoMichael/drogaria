<?php

namespace App\Filament\Resources\OrcamentoResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\OrcamentoResource;

/**
 * Página responsável pela listagem dos registros de Orçamento.
 *
 * Esta classe estende ListRecords e define o resource associado,
 * além de configurar as ações disponíveis no cabeçalho da página,
 * como a ação para criação de um novo registro.
 */
class ListOrcamentos extends ListRecords
{
    /**
     * Define o resource associado a esta página de listagem.
     *
     * @var class-string<OrcamentoResource>
     */
    protected static string $resource = OrcamentoResource::class;

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
