<?php

namespace App\Filament\Resources\GeographicReachResource\Pages;

use App\Filament\Resources\GeographicReachResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGeographicReach extends EditRecord
{
    protected static string $resource = GeographicReachResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
