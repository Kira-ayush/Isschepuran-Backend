<?php

namespace App\Filament\Resources\GeographicReachResource\Pages;

use App\Filament\Resources\GeographicReachResource;
use App\Filament\Widgets\SectionHeadingWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGeographicReaches extends ListRecords
{
    protected static string $resource = GeographicReachResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            SectionHeadingWidget::make([
                'key' => 'geographic-reach',
                'defaultEyebrow' => 'Where we work',
                'defaultHeading' => 'Our Geographic Reach',
                'label' => 'About Page — Section Heading',
            ]),
        ];
    }

    public function getHeaderWidgetsColumns(): int | array
    {
        return 1;
    }
}
