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
        $this->form->fill(SiteSetting::current()->toArray());
    }

    public function form(Schema $schema): Schema
    {
        // Bind the record on the schema every request (not just in mount) —
        // this is what core EditRecord does, and it's what makes the
        // SpatieMediaLibraryFileUpload both SHOW the existing file and SAVE
        // a new one. Binding only in mount()/save() left the field blank.
        return $schema
            ->model(SiteSetting::current())
            ->components([
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
                    // Plain text, not ->tel() — it's displayed as-is on the
                    // site (not a tel: link), and the client lists more than
                    // one number, e.g. "+91 9147708511 | +91 8902339686",
                    // which the tel() format rule rejects.
                    Forms\Components\TextInput::make('phone')
                        ->required()
                        ->helperText('Shown in the footer and on the contact page. Separate multiple numbers with " | ".'),
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
        $state = $this->form->getState();

        unset($state['logo']);
        SiteSetting::current()->update($state);
        $this->form->saveRelationships();

        Notification::make()
            ->title('Site settings saved')
            ->success()
            ->send();
    }
}
