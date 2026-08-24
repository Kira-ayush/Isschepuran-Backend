<?php

namespace App\Filament\Resources\CsrInquiryResource\Pages;

use App\Filament\Resources\CsrInquiryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCsrInquiry extends EditRecord
{
    protected static string $resource = CsrInquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
