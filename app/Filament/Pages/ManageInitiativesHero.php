<?php

namespace App\Filament\Pages;

use App\Models\InitiativesHero;
use BackedEnum;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use UnitEnum;

/**
 * A singleton settings page for the Initiatives page's hero banner — no
 * CTA buttons, matching the actual source content (same pattern as
 * AboutHero).
 */
class ManageInitiativesHero extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationLabel = 'Hero Section';
    protected static string|UnitEnum|null $navigationGroup = 'Initiatives Page';
    protected static ?string $slug = 'initiatives-hero';
    protected string $view = 'filament.pages.manage-initiatives-hero';

    public ?array $data = [];

    public function mount(): void
    {
        $hero = InitiativesHero::current();
        $this->form->model($hero)->fill($hero->toArray());
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
        InitiativesHero::current()->update($state);

        Notification::make()
            ->title('Initiatives hero saved')
            ->success()
            ->send();
    }
}
