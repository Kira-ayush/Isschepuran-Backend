<?php

namespace App\Filament\Resources\SdgAlignmentResource\Pages;

use App\Filament\Resources\SdgAlignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSdgAlignment extends EditRecord
{
    protected static string $resource = SdgAlignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
