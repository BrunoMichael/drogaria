<?php

namespace App\Filament\Resources\VendedorResource\Pages;

use App\Filament\Resources\VendedorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/**
 * Página responsável pela edição de registros de Vendedor.
 *
 * Esta classe estende EditRecord e define o resource associado,
 * além de incluir ações no cabeçalho, como a ação de exclusão.
 */
class EditVendedor extends EditRecord
{
    /**
     * Define o resource associado a esta página de edição.
     *
     * @var class-string<VendedorResource>
     */
    protected static string $resource = VendedorResource::class;

    /**
     * Define as ações disponíveis no cabeçalho da página de edição.
     *
     * @return array<int, \Filament\Actions\Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
