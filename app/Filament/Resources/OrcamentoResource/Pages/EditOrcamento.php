<?php

namespace App\Filament\Resources\OrcamentoResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\OrcamentoResource;
use Filament\Notifications\Notification;


/**
 * Página responsável pela edição de registros de Orçamento.
 *
 * Esta classe estende EditRecord e define o resource associado,
 * além de incluir ações no cabeçalho, como a ação de exclusão.
 */
class EditOrcamento extends EditRecord
{
    /**
     * Define o resource associado a esta página de edição.
     *
     * @var class-string<OrcamentoResource>
     */
    protected static string $resource = OrcamentoResource::class;

    /**
     * Define as ações disponíveis no cabeçalho da página de edição.
     *
     * @return array<int, \Filament\Actions\Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('finalizar')
                ->label('Finalizar')
                ->color('success')
                ->icon('heroicon-o-check')
                ->visible(fn() => $this->record->status === 'rascunho')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->status = 'finalizado';
                    $this->record->save();

                    Notification::make()
                        ->title('Orçamento finalizado com sucesso.')
                        ->success()
                        ->send();
                }),

            Action::make('cancelar')
                ->label('Cancelar')
                ->color('danger')
                ->icon('heroicon-o-x-circle')
                ->visible(fn() => $this->record->status === 'rascunho')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->status = 'cancelado';
                    $this->record->save();
                    
                    Notification::make()
                        ->title('Orçamento cancelado com sucesso.')
                        ->danger()
                        ->send();
                }),
            Actions\DeleteAction::make()
                ->visible(fn() => in_array(Auth::user()?->permission, ['gestor', 'gerente'])),
        ];
    }

    /**
     * Define se a página pode ser acessada pelo usuário autenticado.
     * 
     * Neste caso, apenas usuários com permissão ou permissões podem acessar
     * a página associada a este recurso.
     *
     * @param array $parameters Parâmetros da rota, se houver.
     * @return bool
     */
    public static function canAccess(array $parameters = []): bool
    {
        return in_array(Auth::user()?->permission, ['gestor', 'gerente', 'vendedor']);
    }
}
