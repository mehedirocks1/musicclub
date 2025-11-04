<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    /**
     * Mutate form data before creating the user
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Automatically set email_verified_at to current timestamp
        $data['email_verified_at'] = now();

        return $data;
    }

    /**
     * After the user is created, assign selected roles
     */
    protected function afterCreate($record): void
    {
        // Get selected roles from the form state
        $roles = $this->form->getState()['roles'] ?? [];

        if (!empty($roles)) {
            $record->syncRoles($roles); // Assign roles to the new user
        }
    }
}
