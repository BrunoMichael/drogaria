<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/**
 * Página responsável pela edição de registros de Usuário.
 *
 * Esta classe estende EditRecord e define o resource associado,
 * além de incluir ações no cabeçalho, como a ação de exclusão.
 */
class EditUser extends EditRecord
{
    /**
     * Define o resource associado a esta página de edição.
     *
     * @var class-string<UserResource>
     */
    protected static string $resource = UserResource::class;

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
