<?php

namespace App\Filament\Resources\PessoaResource\Pages;

use App\Filament\Resources\PessoaResource;
use Filament\Resources\Pages\CreateRecord;

/**
 * Página responsável por criar novos registros de Pessoa.
 */
class CreatePessoa extends CreateRecord
{
    /**
     * Define o resource associado a esta página de criação.
     *
     * @var class-string<PessoaResource>
     */
    protected static string $resource = PessoaResource::class;
}
