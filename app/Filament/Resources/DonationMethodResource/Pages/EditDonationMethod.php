<?php

namespace App\Filament\Resources\DonationMethodResource\Pages;

use App\Filament\Resources\DonationMethodResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDonationMethod extends EditRecord
{
    protected static string $resource = DonationMethodResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
