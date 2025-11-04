<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    /**
     * After the user is updated, sync the roles properly
     */
    protected function afterSave(): void
    {
        $record = $this->record;

        // Get roles from form state
        $roles = $this->form->getState()['roles'] ?? [];

        if (!empty($roles)) {
            $record->syncRoles($roles); // Sync roles
        } else {
            $record->syncRoles([]); // Remove all roles if none selected
        }
    }
}
