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
        // Bind the record on the schema every request so the
        // SpatieMediaLibraryFileUpload shows the existing image and saves a
        // new one — see the note in ManageSiteSettings.
        return $schema
            ->model(AboutIntro::current())
            ->components([
            Section::make('Origin Story')
                ->schema([
                    Forms\Components\TextInput::make('origin_title')->required(),
                    Forms\Components\Textarea::make('origin_body')->required()->rows(5),
                    Forms\Components\TextInput::make('established_year')
                        ->required()
                        ->numeric()
                        ->minValue(1900)
                        ->maxValue(now()->year),
                    Forms\Components\SpatieMediaLibraryFileUpload::make('origin_image')
                        ->collection('origin_image')
                        ->image()
                        ->maxSize(10240)
                        ->helperText('Photo shown alongside the origin story. Max file size: 10 MB.'),
                    Forms\Components\TextInput::make('origin_image_alt')
                        ->label('Origin photo alt text')
                        ->helperText('Describes the photo for screen readers and search engines.'),
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
        unset($state['origin_image']);
        AboutIntro::current()->update($state);

        $this->form->saveRelationships();

        Notification::make()
            ->title('About story saved')
            ->success()
            ->send();
    }
}
