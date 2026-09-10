<?php

namespace App\Filament\Resources\PartnerResource\Pages;

use App\Filament\Resources\PartnerResource;
use App\Filament\Widgets\SectionHeadingWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPartners extends ListRecords
{
    protected static string $resource = PartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }

    // The two row labels ("In partnership with" / "Project implemented
    // for") are editable here. Only the `heading` field is rendered on the
    // frontend — the required `eyebrow` is unused for this section, just
    // leave its default.
    protected function getHeaderWidgets(): array
    {
        return [
            SectionHeadingWidget::make([
                'key' => 'partners-partnership',
                'defaultEyebrow' => 'Partners',
                'defaultHeading' => 'In partnership with',
                'label' => 'Row 1 label ("In partnership with")',
            ]),
            SectionHeadingWidget::make([
                'key' => 'partners-implemented-for',
                'defaultEyebrow' => 'Partners',
                'defaultHeading' => 'Project implemented for',
                'label' => 'Row 2 label ("Project implemented for")',
            ]),
        ];
    }

    public function getHeaderWidgetsColumns(): int | array
    {
        return 2;
    }
}
