<?php

namespace App\Filament\Resources\OfertaResource\Pages;

use App\Filament\Resources\OfertaResource;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\CreateRecord;

/**
 * Página responsável por criar novos registros de Oferta.
 *
 * Esta classe herda de CreateRecord e define qual resource ela utiliza.
 */
class CreateOferta extends CreateRecord
{
    /**
     * Define o resource associado a esta página de criação.
     *
     * @var class-string<OfertaResource>
     */
    protected static string $resource = OfertaResource::class;

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
