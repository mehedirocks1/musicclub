<?php

namespace App\Filament\Resources\Abouts\Pages;

use App\Filament\Resources\Abouts\AboutResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAbouts extends ListRecords
{
    protected static string $resource = AboutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function mount(): void
    {
        // ✅ Use getModel() to access the model safely
        $model = AboutResource::getModel();
        $about = $model::first();

        if ($about) {
            $this->redirect(AboutResource::getUrl('edit', ['record' => $about]));
        } else {
            $this->redirect(AboutResource::getUrl('create'));
        }
    }
}
