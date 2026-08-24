<?php

namespace App\Filament\Resources\CsrFeatureResource\Pages;

use App\Filament\Resources\CsrFeatureResource;
use App\Filament\Widgets\SectionHeadingWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCsrFeatures extends ListRecords
{
    protected static string $resource = CsrFeatureResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }

    // This is the "primary" sub-resource of the Corporate Social Synergy
    // section (features + carbon stats + partners, one merged frontend
    // section) — it owns the shared section heading. CsrPartnerResource
    // and CarbonStatResource deliberately don't also register this widget.
    protected function getHeaderWidgets(): array
    {
        return [
            SectionHeadingWidget::make([
                'key' => 'csr-synergy',
                'defaultEyebrow' => 'Partner with purpose',
                'defaultHeading' => 'Corporate Social Synergy',
                'label' => 'Impact Page — Section Heading (shared with CSR Partners & Carbon Stats)',
            ]),
        ];
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return 1;
    }
}
