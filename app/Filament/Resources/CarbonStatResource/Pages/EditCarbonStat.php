<?php

namespace App\Filament\Resources\CarbonStatResource\Pages;

use App\Filament\Resources\CarbonStatResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCarbonStat extends EditRecord
{
    protected static string $resource = CarbonStatResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
