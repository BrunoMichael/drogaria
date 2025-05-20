<?php

namespace App\Filament\Resources\ProdutoResource\Pages;

use App\Filament\Resources\ProdutoResource;
use Filament\Resources\Pages\CreateRecord;

/**
 * Página responsável pela criação de novos registros de Produto.
 *
 * Esta classe estende CreateRecord e define o resource associado.
 */
class CreateProduto extends CreateRecord
{
    /**
     * Define o resource associado a esta página de criação.
     *
     * @var class-string<ProdutoResource>
     */
    protected static string $resource = ProdutoResource::class;
}
