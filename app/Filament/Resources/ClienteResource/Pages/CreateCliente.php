<?php

namespace App\Filament\Resources\ClienteResource\Pages;

use App\Filament\Resources\ClienteResource;
use Filament\Resources\Pages\CreateRecord;

/**
 * Página responsável por criar novos registros de Cliente.
 *
 * Esta classe herda de CreateRecord e define qual resource ela utiliza.
 */
class CreateCliente extends CreateRecord
{
    /**
     * Define o resource associado a esta página de criação.
     *
     * @var class-string<ClienteResource>
     */
    protected static string $resource = ClienteResource::class;
}
