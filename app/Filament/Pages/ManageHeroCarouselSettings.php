<?php

namespace App\Filament\Pages;

use App\Models\HeroCarouselSetting;
use BackedEnum;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use UnitEnum;

/**
 * A singleton settings page (not a Resource — there is only ever one
 * HeroCarouselSetting row) for the two carousel-wide display options:
 * indicator dot style and the gradient overlay toggle. Per-slide content
 * lives on HeroSlideResource instead — these two are global to the whole
 * carousel, not per slide.
 */
class ManageHeroCarouselSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-adjustments-horizontal';
    protected static ?string $navigationLabel = 'Hero Carousel Settings';
    protected static string|UnitEnum|null $navigationGroup = 'Home Page';
    protected static ?string $slug = 'hero-carousel-settings';
    protected string $view = 'filament.pages.manage-hero-carousel-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = HeroCarouselSetting::current();
        $this->form->fill($settings->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\Select::make('indicator_style')
                ->label('Indicator dot style')
                ->options([
                    'circle' => 'Circle',
                    'dot' => 'Dot',
                    'dash' => 'Dash ( - )',
                    'plant' => 'Plant / leaf icon',
                ])
                ->required()
                ->helperText('Controls the slide-position indicators at the bottom of the hero carousel on the live site.'),
            Forms\Components\Toggle::make('gradient_overlay')
                ->label('Gradient overlay')
                ->default(true)
                ->helperText('Darkens every slide\'s background photo so the white text stays readable. Applies to the whole carousel — not per slide.'),
        ])->statePath('data');
    }

    public function save(): void
    {
        HeroCarouselSetting::current()->update($this->form->getState());

        Notification::make()
            ->title('Hero carousel settings saved')
            ->success()
            ->send();
    }
}
