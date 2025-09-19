<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;

class ManageCategories extends ManageRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->createAnother(false)
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Category created')
                        ->body('The category has been created successfully.')
                ),
        ];
    }
}
