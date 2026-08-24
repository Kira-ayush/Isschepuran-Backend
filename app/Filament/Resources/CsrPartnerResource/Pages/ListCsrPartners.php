<?php

namespace App\Filament\Resources\CsrPartnerResource\Pages;

use App\Filament\Resources\CsrPartnerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

// Shares the 'csr-synergy' section heading, edited on CsrFeatureResource's
// list page — see that class's getHeaderWidgets(). Intentionally no
// SectionHeadingWidget registered here.
class ListCsrPartners extends ListRecords
{
    protected static string $resource = CsrPartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
