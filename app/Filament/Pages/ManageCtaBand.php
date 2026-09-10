<?php

namespace App\Filament\Pages;

use App\Models\CtaBand;
use BackedEnum;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

/**
 * A singleton settings page (not a Resource — there is only ever one
 * CtaBand row) for the closing "Join the Movement" band shown at the
 * bottom of every page (Home, About, Initiatives, and any future page),
 * not just Home — so it deliberately sits outside every page-specific nav
 * group, same as ManageSiteSettings.
 */
class ManageCtaBand extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-megaphone';
    protected static ?string $navigationLabel = 'CTA Band';
    protected static ?string $slug = 'cta-band';
    protected string $view = 'filament.pages.manage-cta-band';

    public ?array $data = [];

    public function mount(): void
    {
        $record = CtaBand::current();

        $this->form->fill([
            ...$record->toArray(),
            // Derive which "Text colour" radio option matches the stored hex.
            'text_color_preset' => $this->presetForColor($record->text_color),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        // Bind the record on the schema itself (every request, not just
        // mount) so the SpatieMediaLibraryFileUpload has a model to attach
        // to when save() runs — this is what the core EditRecord/EditProfile
        // pages do.
        return $schema
            ->model(CtaBand::current())
            ->components([
                Forms\Components\TextInput::make('heading')->required(),
                Forms\Components\Textarea::make('subheading')->required()->rows(3),
                Forms\Components\TextInput::make('primary_cta_label')->required(),
                Forms\Components\TextInput::make('primary_cta_href')->required(),
                Forms\Components\TextInput::make('secondary_cta_label')->required(),
                Forms\Components\TextInput::make('secondary_cta_href')->required(),

                Section::make('Text colour')
                    ->description('Colour of the heading and subheading. White suits the dark band and dark photos.')
                    ->schema([
                        Forms\Components\Radio::make('text_color_preset')
                            ->label('Colour')
                            ->options([
                                ...collect(CtaBand::TEXT_COLOR_PRESETS)->map(fn ($p) => $p['label'])->all(),
                                'custom' => 'Custom hex…',
                            ])
                            ->default('white')
                            ->live()
                            ->dehydrated(false),
                        Forms\Components\ColorPicker::make('text_color')
                            ->label('Custom colour')
                            ->visible(fn (Get $get): bool => $get('text_color_preset') === 'custom')
                            ->helperText('Enter or pick a hex value, e.g. #ffffff.'),
                    ]),

                Section::make('Background (optional)')
                    ->description('Leave blank for the default dark-green band.')
                    ->schema([
                        Forms\Components\SpatieMediaLibraryFileUpload::make('background')
                            ->collection('background')
                            ->image()
                            ->maxSize(20480)
                            ->helperText('Full-bleed photo behind the CTA text. Wait for the thumbnail to appear before saving. Max file size: 20 MB.')
                            ->required(false),
                        Forms\Components\TextInput::make('background_alt')
                            ->label('Background image alt text')
                            ->helperText('Describes the photo for screen readers and search engines.'),
                        Forms\Components\Toggle::make('overlay')
                            ->label('Dark gradient overlay')
                            ->helperText('Darkens the photo so the white CTA text stays readable. Only applies when a background image is set.'),
                    ]),
            ])->statePath('data');
    }

    public function save(): void
    {
        $record = CtaBand::current();

        $state = $this->form->getState();

        // Resolve the text colour: a named preset wins over the custom
        // picker unless "custom" is chosen. `text_color_preset` is
        // dehydrated(false) so read it from the raw component state.
        $preset = $this->data['text_color_preset'] ?? 'white';
        $state['text_color'] = $preset === 'custom'
            ? ($this->normalizeHex($state['text_color'] ?? null))
            : (CtaBand::TEXT_COLOR_PRESETS[$preset]['hex'] ?? null);

        unset($state['background']);
        $record->update($state);

        // Model is already bound in form() above; this persists the upload.
        $this->form->saveRelationships();

        Notification::make()
            ->title('CTA band saved')
            ->success()
            ->send();
    }

    private function presetForColor(?string $hex): string
    {
        $hex = $this->normalizeHex($hex);

        foreach (CtaBand::TEXT_COLOR_PRESETS as $key => $preset) {
            if ($this->normalizeHex($preset['hex']) === $hex) {
                return $key;
            }
        }

        return $hex === null ? 'white' : 'custom';
    }

    private function normalizeHex(?string $hex): ?string
    {
        $hex = trim((string) $hex);

        return $hex === '' ? null : Str::lower($hex);
    }
}
