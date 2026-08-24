<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Concerns\ManagesSeoSettings;
use BackedEnum;
use Filament\Pages\Page;
use UnitEnum;

class ManageContactSeo extends Page
{
    use ManagesSeoSettings;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-magnifying-glass';
    protected static ?string $navigationLabel = 'SEO Settings';
    protected static string|UnitEnum|null $navigationGroup = 'Contact Page';
    protected static ?string $slug = 'contact-seo';
    protected string $view = 'filament.pages.manage-seo';

    public function seoKey(): string
    {
        return 'contact';
    }
}
