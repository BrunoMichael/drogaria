<?php

namespace App\Filament\Resources\OfertaResource\Pages;

use App\Filament\Resources\OfertaResource;
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
}
