<?php

namespace App\Filament\Resources\Galleries\Pages;

use App\Filament\Resources\Galleries\GalleryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGallery extends EditRecord
{
    protected static string $resource = GalleryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Standard delete action in the header
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Optional: ensure image field shows the correct path in edit mode
        if (! empty($data['image'])) {
            $data['image'] = $data['image']; // the relative path (gallaries/filename.jpg)
        }

        return $data;
    }
}
