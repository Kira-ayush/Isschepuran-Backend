<?php

namespace App\Filament\Resources\AboutMilestoneResource\Pages;

use App\Filament\Resources\AboutMilestoneResource;
use App\Filament\Widgets\SectionHeadingWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAboutMilestones extends ListRecords
{
    protected static string $resource = AboutMilestoneResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            SectionHeadingWidget::make([
                'key' => 'about-milestones',
                'defaultEyebrow' => 'Our journey',
                'defaultHeading' => 'Milestones of Impact',
                'label' => 'About Page — Section Heading',
            ]),
        ];
    }

    public function getHeaderWidgetsColumns(): int | array
    {
        return 1;
    }
}
