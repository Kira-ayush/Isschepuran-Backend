<?php

namespace App\Filament\Pages;

use App\Models\GetInvolvedHero;
use BackedEnum;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use UnitEnum;

class ManageGetInvolvedHero extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationLabel = 'Hero Section';
    protected static string|UnitEnum|null $navigationGroup = 'Get Involved Page';
    protected static ?string $slug = 'get-involved-hero';
    protected string $view = 'filament.pages.manage-get-involved-hero';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(GetInvolvedHero::current()->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('headline')->required(),
            Forms\Components\Textarea::make('subheading')->required()->rows(3),
            Forms\Components\FileUpload::make('background_image')->image()->directory('heroes')->disk('public'),
            Forms\Components\Select::make('text_alignment')
                ->options([
                    'left' => 'Left',
                    'center' => 'Center',
                    'right' => 'Right',
                ])
                ->default('center')
                ->required(),
            Forms\Components\Toggle::make('show_glassmorphism_button')
                ->label('Show Glassmorphism Toggle Button')
                ->default(true),
        ])->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();
        GetInvolvedHero::current()->update($state);

        Notification::make()
            ->title('Get Involved hero saved')
            ->success()
            ->send();
    }
}
