<?php

namespace App\Filament\Pages;

use App\Models\ContactHero;
use BackedEnum;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use UnitEnum;

class ManageContactHero extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationLabel = 'Hero Section';
    protected static string|UnitEnum|null $navigationGroup = 'Contact Page';
    protected static ?string $slug = 'contact-hero';
    protected string $view = 'filament.pages.manage-contact-hero';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(ContactHero::current()->toArray());
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
        ContactHero::current()->update($state);

        Notification::make()
            ->title('Contact hero saved')
            ->success()
            ->send();
    }
}
