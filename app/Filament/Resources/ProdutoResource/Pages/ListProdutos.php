<?php

namespace App\Filament\Resources\ProdutoResource\Pages;

use App\Filament\Resources\ProdutoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/**
 * Página responsável pela listagem dos registros de Produto.
 *
 * Esta classe estende ListRecords e define o resource associado,
 * além de configurar as ações disponíveis no cabeçalho da página,
 * como a ação para criação de um novo registro.
 */
class ListProdutos extends ListRecords
{
    /**
     * Define o resource associado a esta página de listagem.
     *
     * @var class-string<ProdutoResource>
     */
    protected static string $resource = ProdutoResource::class;

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
