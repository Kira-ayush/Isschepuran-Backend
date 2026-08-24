<?php

namespace App\Filament\Resources\TestimonialResource\Pages;

use App\Filament\Resources\TestimonialResource;
use App\Filament\Widgets\SectionHeadingWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTestimonials extends ListRecords
{
    protected static string $resource = TestimonialResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }

    // These records (Saraswati Devi, Rahul Mondal) are shown on both Home
    // and Impact pages, but each page's framing copy is edited
    // independently — two SectionHeadingWidget keys, one admin screen.
    protected function getHeaderWidgets(): array
    {
        return [
            SectionHeadingWidget::make([
                'key' => 'testimonials',
                'defaultEyebrow' => 'Voices of impact',
                'defaultHeading' => 'Real stories from the communities we serve',
                'label' => 'Home Page — Section Heading',
            ]),
            SectionHeadingWidget::make([
                'key' => 'impact-testimonials',
                'defaultEyebrow' => 'Voices of impact',
                'defaultHeading' => 'Faces of Impact',
                'label' => 'Impact Page — Section Heading',
            ]),
        ];
    }

    public function getHeaderWidgetsColumns(): int | array
    {
        return 1;
    }
}
