<?php

namespace App\Filament\Resources\OfertaResource\Pages;

use App\Filament\Resources\OfertaResource;
use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\EditRecord;

/**
 * Página responsável pela edição de registros de Oferta.
 *
 * Esta classe estende EditRecord e define o resource associado,
 * além de incluir ações no cabeçalho, como a ação de exclusão.
 */
class EditOferta extends EditRecord
{
    /**
     * Define o resource associado a esta página de edição.
     *
     * @var class-string<OfertaResource>
     */
    protected static string $resource = OfertaResource::class;

    /**
     * Define as ações disponíveis no cabeçalho da página de edição.
     *
     * @return array<int, \Filament\Actions\Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->visible(fn () => Auth::user()?->permission === 'gestor'),
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
