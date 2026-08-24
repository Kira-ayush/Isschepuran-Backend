<?php

namespace App\Filament\Resources\TrustBadgeResource\Pages;

use App\Filament\Resources\TrustBadgeResource;
use App\Filament\Widgets\SectionHeadingWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTrustBadges extends ListRecords
{
    protected static string $resource = TrustBadgeResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            SectionHeadingWidget::make([
                'key' => 'trust-badges',
                'defaultEyebrow' => 'Transparency & trust',
                'defaultHeading' => 'Certified & Accountable',
                'label' => 'About Page — Section Heading',
            ]),
        ];
    }

    public function getHeaderWidgetsColumns(): int | array
    {
        return 1;
    }
}
