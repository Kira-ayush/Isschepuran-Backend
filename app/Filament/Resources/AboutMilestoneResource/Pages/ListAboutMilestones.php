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

    // These records (the Cyclone Yaas founding timeline) are shown on both
    // About and Impact pages — Impact reuses this real timeline instead of
    // the original site's geographically-inconsistent placeholder content
    // (see ImpactPageSeeder's docblock). Each page's framing copy is
    // edited independently — two SectionHeadingWidget keys, one admin
    // screen.
    protected function getHeaderWidgets(): array
    {
        return [
            SectionHeadingWidget::make([
                'key' => 'about-milestones',
                'defaultEyebrow' => 'Our journey',
                'defaultHeading' => 'Milestones of Impact',
                'label' => 'About Page — Section Heading',
            ]),
            SectionHeadingWidget::make([
                'key' => 'impact-milestones',
                'defaultEyebrow' => 'Our journey',
                'defaultHeading' => 'Journey of Impact',
                'label' => 'Impact Page — Section Heading',
            ]),
        ];
    }

    public function getHeaderWidgetsColumns(): int | array
    {
        return 1;
    }
}
