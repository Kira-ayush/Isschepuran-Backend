<?php

namespace App\Filament\Resources\AboutMilestoneResource\Pages;

use App\Filament\Resources\AboutMilestoneResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAboutMilestone extends EditRecord
{
    protected static string $resource = AboutMilestoneResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
