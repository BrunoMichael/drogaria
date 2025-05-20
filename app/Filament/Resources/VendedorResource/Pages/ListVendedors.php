<?php

namespace App\Filament\Resources\VendedorResource\Pages;

use App\Filament\Resources\VendedorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/**
 * Página responsável pela listagem dos registros de Vendedor.
 *
 * Esta classe estende ListRecords e define o resource associado,
 * além de configurar as ações disponíveis no cabeçalho da página,
 * como a ação para criação de um novo registro.
 */
class ListVendedors extends ListRecords
{
    /**
     * Define o resource associado a esta página de listagem.
     *
     * @var class-string<VendedorResource>
     */
    protected static string $resource = VendedorResource::class;

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
