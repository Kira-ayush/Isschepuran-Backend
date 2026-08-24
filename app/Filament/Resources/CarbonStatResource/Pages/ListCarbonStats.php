<?php

namespace App\Filament\Resources\CarbonStatResource\Pages;

use App\Filament\Resources\CarbonStatResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

// Shares the 'csr-synergy' section heading, edited on CsrFeatureResource's
// list page — see that class's getHeaderWidgets(). Intentionally no
// SectionHeadingWidget registered here.
class ListCarbonStats extends ListRecords
{
    protected static string $resource = CarbonStatResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
