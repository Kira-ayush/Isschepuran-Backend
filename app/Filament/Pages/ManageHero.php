<?php

namespace App\Filament\Pages;

use App\Models\Hero;
use BackedEnum;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use UnitEnum;

/**
 * A singleton settings page (not a Resource — there is only ever one
 * Hero row) for the Home page's hero section: eyebrow, headline,
 * subheading, CTAs, and background image.
 */
class ManageHero extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationLabel = 'Hero Section';
    protected static string|UnitEnum|null $navigationGroup = 'Home Page';
    protected static ?string $slug = 'hero';
    protected string $view = 'filament.pages.manage-hero';

    public ?array $data = [];

    public function mount(): void
    {
        $hero = Hero::current();
        $this->form->model($hero)->fill($hero->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('eyebrow')
                ->required()
                ->helperText('Small label above the headline, e.g. "Reforestation · Water · Education"'),
            Forms\Components\Textarea::make('headline')
                ->required()
                ->rows(2)
                ->helperText('Use a line break for a two-line headline.'),
            Forms\Components\Textarea::make('subheading')
                ->required()
                ->rows(3),
            Forms\Components\TextInput::make('primary_cta_label')->required(),
            Forms\Components\TextInput::make('primary_cta_href')->required(),
            Forms\Components\TextInput::make('secondary_cta_label')->required(),
            Forms\Components\TextInput::make('secondary_cta_href')->required(),
            Forms\Components\SpatieMediaLibraryFileUpload::make('background')
                ->collection('background')
                ->image()
                ->maxSize(10240)
                ->helperText('Full-bleed background photo behind the hero text. Max file size: 10 MB.'),
            Forms\Components\TextInput::make('background_alt')
                ->label('Background image alt text')
                ->helperText('Describes the photo for screen readers and search engines, e.g. "Volunteers planting mangrove saplings at sunrise."'),
        ])->statePath('data');
    }

    public function save(): void
    {
        $hero = Hero::current();

        $state = $this->form->getState();
        unset($state['background']);
        $hero->update($state);

        $this->form->model($hero)->saveRelationships();

        Notification::make()
            ->title('Hero section saved')
            ->success()
            ->send();
    }
}
