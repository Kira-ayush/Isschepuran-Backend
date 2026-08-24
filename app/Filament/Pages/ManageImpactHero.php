<?php

namespace App\Filament\Pages;

use App\Models\ImpactHero;
use BackedEnum;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use UnitEnum;

/**
 * A singleton settings page for the Impact page's hero banner — no CTA
 * buttons or image, same minimal shape as About/Initiatives' hero. No
 * source copy existed for this in docs/raw-site-content.md (the original
 * live Impact page had no documented hero section); the seeded default in
 * ImpactHero::current() is drafted copy, not migrated real content — see
 * ImpactPageSeeder's docblock.
 */
class ManageImpactHero extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationLabel = 'Hero Section';
    protected static string|UnitEnum|null $navigationGroup = 'Impact Page';
    protected static ?string $slug = 'impact-hero';
    protected string $view = 'filament.pages.manage-impact-hero';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(ImpactHero::current()->toArray());
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
        ImpactHero::current()->update($state);

        Notification::make()
            ->title('Impact hero saved')
            ->success()
            ->send();
    }
}
