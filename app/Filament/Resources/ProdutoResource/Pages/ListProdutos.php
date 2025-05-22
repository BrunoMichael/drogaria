<?php

namespace App\Filament\Resources\ProdutoResource\Pages;

use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\ProdutoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/**
 * Página responsável pela listagem dos registros de Produto.
 *
 * Esta classe estende ListRecords e define o resource associado,
 * além de configurar as ações disponíveis no cabeçalho da página,
 * como a ação para criação de um novo registro.
 */
class ListProdutos extends ListRecords
{
    /**
     * Define o resource associado a esta página de listagem.
     *
     * @var class-string<ProdutoResource>
     */
    protected static string $resource = ProdutoResource::class;

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
        return in_array(Auth::user()?->permission, ['gestor', 'gerente']);
    }
}
