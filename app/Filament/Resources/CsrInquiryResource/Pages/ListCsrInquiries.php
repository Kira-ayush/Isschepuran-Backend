<?php

namespace App\Filament\Resources\CsrInquiryResource\Pages;

use App\Filament\Resources\CsrInquiryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCsrInquiries extends ListRecords
{
    protected static string $resource = CsrInquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
