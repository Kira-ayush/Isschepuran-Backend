<?php

namespace App\Filament\Resources\CsrFeatureResource\Pages;

use App\Filament\Resources\CsrFeatureResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCsrFeature extends EditRecord
{
    protected static string $resource = CsrFeatureResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
