<?php

namespace App\Filament\Resources\DonationMethodResource\Pages;

use App\Filament\Resources\DonationMethodResource;
use App\Filament\Widgets\SectionHeadingWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDonationMethods extends ListRecords
{
    protected static string $resource = DonationMethodResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }

    // This is the only real list-style Resource on the Get Involved page,
    // so it hosts the section headings for all 3 sections on that page —
    // same "one page hosts multiple related section headings" precedent
    // as Impact's csr-synergy key.
    protected function getHeaderWidgets(): array
    {
        return [
            SectionHeadingWidget::make([
                'key' => 'donation-methods',
                'defaultEyebrow' => 'Ways to give',
                'defaultHeading' => 'Choose How to Give',
                'label' => 'Get Involved Page — Donate Section Heading',
            ]),
            SectionHeadingWidget::make([
                'key' => 'volunteer',
                'defaultEyebrow' => 'Give your time',
                'defaultHeading' => 'Volunteer With Us',
                'label' => 'Get Involved Page — Volunteer Section Heading',
            ]),
            SectionHeadingWidget::make([
                'key' => 'csr-partnership',
                'defaultEyebrow' => 'Partner with purpose',
                'defaultHeading' => 'CSR & Corporate Partnerships',
                'label' => 'Get Involved Page — CSR Section Heading',
            ]),
        ];
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return 1;
    }
}
