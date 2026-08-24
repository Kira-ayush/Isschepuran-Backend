<?php

namespace App\Filament\Pages\Concerns;

use App\Models\SeoSetting;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

/**
 * Shared form/mount/save for the per-page "SEO Settings" singleton pages
 * (ManageHomeSeo, ManageAboutSeo, ManageInitiativesSeo, ...). Each concrete
 * page only needs to set its own navigation properties and `seoKey()` —
 * everything else (the 8 SEO fields, media handling) is identical across
 * pages, unlike the other singleton pages in this app which have genuinely
 * different fields per page.
 */
trait ManagesSeoSettings
{
    public ?array $data = [];

    abstract public function seoKey(): string;

    public function mount(): void
    {
        $seo = SeoSetting::forKey($this->seoKey());
        $this->form->model($seo)->fill($seo->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Search (Google, etc.)')
                ->description('Falls back to this page\'s normal content-derived title/description whenever left blank.')
                ->schema([
                    Forms\Components\TextInput::make('meta_title')
                        ->label('Meta title'),
                    Forms\Components\Textarea::make('meta_description')
                        ->label('Meta description')
                        ->rows(3),
                    Forms\Components\Placeholder::make('canonical_note')
                        ->label('Canonical URL')
                        ->content('Generated automatically on the frontend from this page\'s route and live domain — not editable here.'),
                ]),

            Section::make('Facebook / Open Graph')
                ->schema([
                    Forms\Components\TextInput::make('og_title')
                        ->label('OG title')
                        ->helperText('Falls back to the meta title above when left blank.'),
                    Forms\Components\Textarea::make('og_description')
                        ->label('OG description')
                        ->rows(3)
                        ->helperText('Falls back to the meta description above when left blank.'),
                    Forms\Components\SpatieMediaLibraryFileUpload::make('og_image')
                        ->collection('og_image')
                        ->image()
                        ->maxSize(10240)
                        ->helperText('Recommended 1200×630. Max file size: 10 MB.'),
                ]),

            Section::make('Twitter / X')
                ->schema([
                    Forms\Components\TextInput::make('twitter_title')
                        ->label('Twitter title')
                        ->helperText('Falls back to the OG title (or meta title) when left blank.'),
                    Forms\Components\Textarea::make('twitter_description')
                        ->label('Twitter description')
                        ->rows(3)
                        ->helperText('Falls back to the OG description (or meta description) when left blank.'),
                    Forms\Components\SpatieMediaLibraryFileUpload::make('twitter_image')
                        ->collection('twitter_image')
                        ->image()
                        ->maxSize(10240)
                        ->helperText('Falls back to the OG image when left blank. Max file size: 10 MB.'),
                ]),
        ])->statePath('data');
    }

    public function save(): void
    {
        $seo = SeoSetting::forKey($this->seoKey());

        $state = $this->form->getState();
        unset($state['og_image'], $state['twitter_image']);
        $seo->update($state);

        $this->form->model($seo)->saveRelationships();

        Notification::make()
            ->title('SEO settings saved')
            ->success()
            ->send();
    }
}
