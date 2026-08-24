<?php

namespace App\Filament\Pages;

use App\Models\CtaBand;
use BackedEnum;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;

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
        $this->form->fill(CtaBand::current()->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('heading')->required(),
            Forms\Components\Textarea::make('subheading')->required()->rows(3),
            Forms\Components\TextInput::make('primary_cta_label')->required(),
            Forms\Components\TextInput::make('primary_cta_href')->required(),
            Forms\Components\TextInput::make('secondary_cta_label')->required(),
            Forms\Components\TextInput::make('secondary_cta_href')->required(),
        ])->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();
        CtaBand::current()->update($state);

        Notification::make()
            ->title('CTA band saved')
            ->success()
            ->send();
    }
}
