<?php

namespace App\Filament\Pages;

use App\Models\HomeVideoHero;
use BackedEnum;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use UnitEnum;

/**
 * A singleton settings page (not a Resource — there is only ever one
 * HomeVideoHero row) for the optional full-bleed video hero band that
 * renders ABOVE the image carousel on the Home page. Per-slide carousel
 * content still lives on HeroSlideResource; the carousel-wide display
 * options on ManageHeroCarouselSettings — this is a separate section.
 *
 * Handles Spatie media on a singleton Page the same way ManageSiteSettings
 * does (the only other singleton Page with uploads): bind
 * $this->form->model(...) in mount(), unset the upload keys + call
 * saveRelationships() in save().
 */
class ManageHomeVideoHero extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-film';
    protected static ?string $navigationLabel = 'Video Hero Section';
    protected static string|UnitEnum|null $navigationGroup = 'Home Page';
    protected static ?int $navigationSort = -1; // list above "Hero Slides"
    protected static ?string $slug = 'home-video-hero';
    protected string $view = 'filament.pages.manage-home-video-hero';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(HomeVideoHero::current()->toArray());
    }

    public function form(Schema $schema): Schema
    {
        // Bind the record on the schema every request so the
        // SpatieMediaLibraryFileUpload fields show the existing video/poster
        // and save new ones — see the note in ManageSiteSettings.
        return $schema
            ->model(HomeVideoHero::current())
            ->components([
            Forms\Components\Toggle::make('is_enabled')
                ->label('Show the video hero section on the Home page')
                ->helperText('When off, the Home page opens straight into the image carousel as before — nothing else here matters until this is on.'),

            Section::make('Text overlay')
                ->schema([
                    Forms\Components\TextInput::make('eyebrow')
                        ->helperText('Small label above the headline, e.g. "Our Mission".'),
                    Forms\Components\Textarea::make('headline')
                        ->rows(2)
                        ->helperText('The large heading over the video. When set, this becomes the page\'s main <h1> and the carousel\'s first slide steps down a level.'),
                    Forms\Components\Textarea::make('subheading')
                        ->rows(3),
                ]),

            Section::make('Video')
                ->schema([
                    Forms\Components\SpatieMediaLibraryFileUpload::make('video')
                        ->collection('video')
                        ->acceptedFileTypes(['video/mp4', 'video/webm'])
                        ->maxSize(204800)
                        ->helperText('MP4 or WebM, up to 200 MB. Use a short, muted, loopable clip — it autoplays with no sound. Large files: host on a CDN and use the URL field below instead. (Production upload limit also depends on the server\'s PHP settings.)'),
                    Forms\Components\TextInput::make('video_url')
                        ->label('External video URL')
                        ->url()
                        ->helperText('A direct link to an .mp4/.webm file (CDN, S3, etc.). Used only when no file is uploaded above.'),
                ]),

            Section::make('Poster image')
                ->schema([
                    Forms\Components\SpatieMediaLibraryFileUpload::make('poster')
                        ->collection('poster')
                        ->image()
                        ->maxSize(10240)
                        ->helperText('Shown while the video loads, on slow connections, and when motion is reduced. Also used as the section\'s fallback if no video is set. Max file size: 10 MB.'),
                    Forms\Components\TextInput::make('poster_alt')
                        ->label('Poster image alt text')
                        ->helperText('Describes the image for screen readers and search engines.'),
                ]),

            Section::make('Buttons')
                ->description('Up to three. A button appears only when it has both a label and a link.')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('cta1_label')->label('Button 1 label'),
                    Forms\Components\TextInput::make('cta1_href')->label('Button 1 link'),
                    Forms\Components\TextInput::make('cta2_label')->label('Button 2 label'),
                    Forms\Components\TextInput::make('cta2_href')->label('Button 2 link'),
                    Forms\Components\TextInput::make('cta3_label')->label('Button 3 label'),
                    Forms\Components\TextInput::make('cta3_href')->label('Button 3 link'),
                ]),

            Section::make('Display')
                ->schema([
                    Forms\Components\Toggle::make('overlay')
                        ->label('Dark gradient overlay')
                        ->helperText('Darkens the video so the white overlay text stays readable. Turn off only for already-dark footage.'),
                ]),
        ])->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();
        unset($state['video'], $state['poster']);
        HomeVideoHero::current()->update($state);

        $this->form->saveRelationships();

        Notification::make()
            ->title('Video hero section saved')
            ->success()
            ->send();
    }
}
