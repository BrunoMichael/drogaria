<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Pessoa;

/**
 * Página responsável pela criação de novos registros de usuário.
 *
 * Esta classe estende CreateRecord e define o resource associado.
 * 
 * Antes de criar o usuário, altera os dados do formulário para sincronizar
 * informações da entidade Pessoa relacionada.
 */
class CreateUser extends CreateRecord
{
    /**
     * Define o resource associado a esta página de criação.
     *
     * @var class-string<UserResource>
     */
    protected static string $resource = UserResource::class;

    /**
     * Modifica os dados do formulário antes de criar o registro no banco.
     *
     * Realiza a busca da Pessoa relacionada pelo 'pessoa_id' enviado no formulário,
     * atualiza o email e marca como vendedor, salvando as alterações na tabela Pessoa.
     * Ajusta também o nome do usuário com base no nome da Pessoa.
     *
     * @param array<string, mixed> $data Dados do formulário enviados para criação
     * @return array<string, mixed> Dados modificados a serem usados na criação do usuário
     *
     * @throws \Exception Se a Pessoa não for encontrada
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $pessoa = Pessoa::find($data['pessoa_id'] ?? null);

        if (!$pessoa) {
            throw new \Exception('Pessoa inválida.');
        }

        $pessoa->email = $data['email'];
        $pessoa->eh_vendedor = true;
        $pessoa->save();

        $data['name'] = $pessoa->nome;

        return $data;
    }
}
