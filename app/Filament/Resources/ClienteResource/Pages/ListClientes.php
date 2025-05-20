<?php

namespace App\Filament\Resources\ClienteResource\Pages;

use App\Filament\Resources\ClienteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/**
 * Página responsável pela listagem dos registros de Cliente.
 *
 * Esta classe estende ListRecords e define o resource associado,
 * além de incluir ações no cabeçalho, como a ação para criar novo registro.
 */
class ListClientes extends ListRecords
{
    /**
     * Define o resource associado a esta página de listagem.
     *
     * @var class-string<ClienteResource>
     */
    protected static string $resource = ClienteResource::class;

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
}
