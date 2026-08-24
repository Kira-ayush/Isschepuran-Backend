<?php

namespace App\Filament\Pages;

use App\Models\AboutIntro;
use BackedEnum;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use UnitEnum;

/**
 * A singleton settings page for the About page's origin story and
 * vision/mission — the main narrative block of the page.
 */
class ManageAboutIntro extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Story & Vision';
    protected static string|UnitEnum|null $navigationGroup = 'About Page';
    protected static ?string $slug = 'about-intro';
    protected string $view = 'filament.pages.manage-about-intro';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(AboutIntro::current()->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Origin Story')
                ->schema([
                    Forms\Components\TextInput::make('origin_title')->required(),
                    Forms\Components\Textarea::make('origin_body')->required()->rows(5),
                    Forms\Components\TextInput::make('established_year')
                        ->required()
                        ->numeric()
                        ->minValue(1900)
                        ->maxValue(now()->year),
                ]),

            Section::make('Vision & Mission')
                ->schema([
                    Forms\Components\Textarea::make('vision')->required()->rows(3),
                    Forms\Components\Textarea::make('mission')->required()->rows(3),
                ]),
        ])->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();
        AboutIntro::current()->update($state);

        Notification::make()
            ->title('About story saved')
            ->success()
            ->send();
    }
}
