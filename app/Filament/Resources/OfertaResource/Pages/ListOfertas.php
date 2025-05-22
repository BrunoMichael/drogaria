<?php

namespace App\Filament\Resources\OfertaResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\OfertaResource;

/**
 * Página responsável pela listagem dos registros de Oferta.
 *
 * Esta classe estende ListRecords e define o resource associado,
 * além de incluir ações no cabeçalho, como a ação para criação de novos registros.
 */
class ListOfertas extends ListRecords
{
    /**
     * Define o resource associado a esta página de listagem.
     *
     * @var class-string<OfertaResource>
     */
    protected static string $resource = OfertaResource::class;

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
