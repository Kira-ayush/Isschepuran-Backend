<?php

namespace App\Filament\Resources\SdgAlignmentResource\Pages;

use App\Filament\Resources\SdgAlignmentResource;
use App\Filament\Widgets\SectionHeadingWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSdgAlignments extends ListRecords
{
    protected static string $resource = SdgAlignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            SectionHeadingWidget::make([
                'key' => 'sdg-alignment',
                'defaultEyebrow' => 'Global commitments',
                'defaultHeading' => 'Aligned with the UN Sustainable Development Goals',
                'label' => 'Home + Impact Pages — Section Heading',
            ]),
        ];
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return 1;
    }
}
