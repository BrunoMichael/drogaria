<?php

namespace App\Filament\Resources\PessoaResource\Pages;

use App\Filament\Resources\PessoaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/**
 * Página responsável pela listagem dos registros de Pessoa.
 */
class ListPessoas extends ListRecords
{
    /**
     * Define o resource associado a esta página de listagem.
     *
     * @var class-string<PessoaResource>
     */
    protected static string $resource = PessoaResource::class;

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
