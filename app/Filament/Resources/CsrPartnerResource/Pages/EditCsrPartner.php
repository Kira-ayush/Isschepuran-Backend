<?php

namespace App\Filament\Resources\CsrPartnerResource\Pages;

use App\Filament\Resources\CsrPartnerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCsrPartner extends EditRecord
{
    protected static string $resource = CsrPartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
