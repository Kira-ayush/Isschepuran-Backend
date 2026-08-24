<?php

namespace App\Filament\Pages;

use App\Models\GalleryHero;
use BackedEnum;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use UnitEnum;

/**
 * A singleton settings page for the Gallery page's hero banner — no CTA
 * buttons or image, same minimal shape as About/Initiatives/Impact's
 * hero. No source copy exists for this in docs/raw-site-content.md (the
 * original live Gallery page was confirmed completely empty); the seeded
 * default in GalleryHero::current() is drafted copy, not migrated real
 * content — see GalleryPageSeeder's docblock.
 */
class ManageGalleryHero extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationLabel = 'Hero Section';
    protected static string|UnitEnum|null $navigationGroup = 'Gallery Page';
    protected static ?string $slug = 'gallery-hero';
    protected string $view = 'filament.pages.manage-gallery-hero';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(GalleryHero::current()->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('headline')->required(),
            Forms\Components\Textarea::make('subheading')->required()->rows(3),
        ])->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();
        GalleryHero::current()->update($state);

        Notification::make()
            ->title('Gallery hero saved')
            ->success()
            ->send();
    }
}
