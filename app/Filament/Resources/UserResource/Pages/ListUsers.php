<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/**
 * Página responsável pela listagem dos registros de Usuário.
 *
 * Esta classe estende ListRecords e define o resource associado,
 * além de configurar as ações disponíveis no cabeçalho da página,
 * como a ação para criação de um novo registro.
 */
class ListUsers extends ListRecords
{
    /**
     * Define o resource associado a esta página de listagem.
     *
     * @var class-string<UserResource>
     */
    protected static string $resource = UserResource::class;

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

    public static function canAccess(array $parameters = []): bool
    {
        return auth()->user()->permission === 'vendedor';
    }
}
