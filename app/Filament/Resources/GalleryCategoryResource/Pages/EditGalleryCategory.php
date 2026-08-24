<?php

namespace App\Filament\Resources\GalleryCategoryResource\Pages;

use App\Filament\Resources\GalleryCategoryResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditGalleryCategory extends EditRecord
{
    protected static string $resource = GalleryCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // The category_id foreign key is restrictOnDelete() — deleting a
            // category still assigned to gallery items would otherwise throw
            // a raw database exception. Block it here with a clear message
            // instead — same pattern as Initiative's EditCategory.
            Actions\DeleteAction::make()
                ->before(function (Actions\DeleteAction $action) {
                    if ($this->record->galleryItems()->exists()) {
                        Notification::make()
                            ->title('Can\'t delete this category')
                            ->body('It\'s still assigned to one or more gallery items. Reassign them to a different category first.')
                            ->danger()
                            ->send();

                        $action->cancel();
                    }
                }),
        ];
    }
}
