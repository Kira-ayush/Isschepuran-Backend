<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // The category_id foreign key is restrictOnDelete() — deleting a
            // category still assigned to initiatives would otherwise throw a
            // raw database exception. Block it here with a clear message
            // instead (same "don't let a DB error reach a raw error page"
            // principle as the file-upload maxSize() fix).
            Actions\DeleteAction::make()
                ->before(function (Actions\DeleteAction $action) {
                    if ($this->record->initiatives()->exists()) {
                        Notification::make()
                            ->title('Can\'t delete this category')
                            ->body('It\'s still assigned to one or more initiatives. Reassign them to a different category first.')
                            ->danger()
                            ->send();

                        $action->cancel();
                    }
                }),
        ];
    }
}
