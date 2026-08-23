<?php

namespace App\Filament\Resources\InitiativeResource\Pages;

use App\Filament\Resources\InitiativeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInitiatives extends ListRecords
{
    protected static string $resource = InitiativeResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
