<?php

namespace App\Filament\Resources\OrcamentoResource\Pages;

use App\Filament\Resources\OrcamentoResource;
use Filament\Resources\Pages\CreateRecord;

/**
 * Página responsável por criar novos registros de Orçamento.
 *
 * Esta classe estende CreateRecord e define o resource relacionado.
 */
class CreateOrcamento extends CreateRecord
{
    /**
     * Define o resource associado a esta página de criação.
     *
     * @var class-string<OrcamentoResource>
     */
    protected static string $resource = OrcamentoResource::class;
}
