<?php

namespace App\Filament\Pages;

use App\Models\AboutHero;
use BackedEnum;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use UnitEnum;

/**
 * A singleton settings page for the About page's hero banner — no CTA
 * buttons, unlike Home's Hero, matching the actual source content.
 */
class ManageAboutHero extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationLabel = 'Hero Section';
    protected static string|UnitEnum|null $navigationGroup = 'About Page';
    protected static ?string $slug = 'about-hero';
    protected string $view = 'filament.pages.manage-about-hero';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(AboutHero::current()->toArray());
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
        AboutHero::current()->update($state);

        Notification::make()
            ->title('About hero saved')
            ->success()
            ->send();
    }
}
