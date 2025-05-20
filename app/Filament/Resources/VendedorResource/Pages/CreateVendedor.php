<?php

namespace App\Filament\Resources\VendedorResource\Pages;

use App\Filament\Resources\VendedorResource;
use Filament\Resources\Pages\CreateRecord;

/**
 * Página responsável pela criação de novos registros de Vendedor.
 *
 * Esta classe estende CreateRecord e define o resource associado.
 */
class CreateVendedor extends CreateRecord
{
    /**
     * Define o resource associado a esta página de criação.
     *
     * @var class-string<VendedorResource>
     */
    protected static string $resource = VendedorResource::class;
}
