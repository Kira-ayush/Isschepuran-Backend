<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use BackedEnum;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

/**
 * A singleton settings page (not a Resource — there is only ever one
 * SiteSetting row) for everything global/site-wide: org info, contact
 * details, nav links, social links, and the donate button destination.
 * This is what finally replaces the old static site's footer/header
 * content that could only be changed by editing HTML.
 */
class ManageSiteSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Site Settings';
    protected static ?string $slug = 'site-settings';
    protected string $view = 'filament.pages.manage-site-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = SiteSetting::current();
        $this->form->model($settings)->fill($settings->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Branding')
                ->schema([
                    Forms\Components\SpatieMediaLibraryFileUpload::make('logo')
                        ->collection('logo')
                        ->image()
                        ->maxSize(10240)
                        ->helperText('Upload the organization logo used in the header/footer. Max file size: 10 MB.'),
                    Forms\Components\TextInput::make('logo_alt')
                        ->label('Logo alt text')
                        ->helperText('Accessible description of the brand logo.'),
                    Forms\Components\TextInput::make('org_name')->required(),
                    Forms\Components\Textarea::make('tagline')->required()->rows(2),
                ]),

            Section::make('Contact')
                ->schema([
                    Forms\Components\TextInput::make('phone')->tel()->required(),
                    Forms\Components\TextInput::make('email')->email()->required(),
                    Forms\Components\Textarea::make('address')->required()->rows(2),
                ]),

            Section::make('Navigation')
                ->schema([
                    Forms\Components\Repeater::make('nav_links')
                        ->schema([
                            Forms\Components\TextInput::make('label')->required(),
                            Forms\Components\TextInput::make('href')->required(),
                        ])
                        ->columns(2)
                        ->reorderable()
                        ->helperText('Controls the header + footer nav — add, remove, or reorder pages here without touching code.'),
                ]),

            Section::make('Social & Donate')
                ->schema([
                    Forms\Components\Repeater::make('social_links')
                        ->schema([
                            Forms\Components\TextInput::make('label')->required(),
                            Forms\Components\TextInput::make('href')->required()->url(),
                        ])
                        ->columns(2),
                    Forms\Components\TextInput::make('donate_href')
                        ->required()
                        ->helperText('Where every "Donate Now" button on the site links to.'),
                ]),
        ])->statePath('data');
    }

    public function save(): void
    {
        $settings = SiteSetting::current();
        $state = $this->form->getState();

        unset($state['logo']);
        $settings->update($state);
        $this->form->model($settings)->saveRelationships();

        Notification::make()
            ->title('Site settings saved')
            ->success()
            ->send();
    }
}
