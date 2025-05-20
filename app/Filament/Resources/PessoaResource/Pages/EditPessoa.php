<?php

namespace App\Filament\Resources\PessoaResource\Pages;

use App\Filament\Resources\PessoaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/**
 * Página responsável pela edição de registros de Pessoa.
 */
class EditPessoa extends EditRecord
{
    /**
     * Define o resource associado a esta página de edição.
     *
     * @var class-string<PessoaResource>
     */
    protected static string $resource = PessoaResource::class;

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
