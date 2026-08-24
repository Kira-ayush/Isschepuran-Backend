<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Concerns\ManagesSeoSettings;
use BackedEnum;
use Filament\Pages\Page;
use UnitEnum;

class ManageInitiativesSeo extends Page
{
    use ManagesSeoSettings;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-magnifying-glass';
    protected static ?string $navigationLabel = 'SEO Settings';
    protected static string|UnitEnum|null $navigationGroup = 'Initiatives Page';
    protected static ?string $slug = 'initiatives-seo';
    protected string $view = 'filament.pages.manage-seo';

    public function seoKey(): string
    {
        return 'initiatives';
    }
}
