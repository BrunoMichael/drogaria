<?php

namespace App\Filament\Resources\OfertaResource\Pages;

use App\Filament\Resources\OfertaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

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
}
