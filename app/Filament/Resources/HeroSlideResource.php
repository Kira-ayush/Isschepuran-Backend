<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HeroSlideResource\Pages;
use App\Models\HeroSlide;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class HeroSlideResource extends Resource
{
    protected static ?string $model = HeroSlide::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-photo';
    protected static string|UnitEnum|null $navigationGroup = 'Home Page';
    protected static ?string $navigationLabel = 'Hero Slides';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('eyebrow')
                ->required()
                ->helperText('Small label above the headline, e.g. "Reforestation · Water · Education"'),
            Forms\Components\Textarea::make('headline')
                ->required()
                ->rows(2)
                ->helperText('Use a line break for a two-line headline. Only the slide with the lowest Order value (below) renders this as the page\'s one <h1> — every other slide looks identical but uses a different tag, for accessibility.'),
            Forms\Components\Textarea::make('subheading')
                ->required()
                ->rows(3),
            Forms\Components\TextInput::make('primary_cta_label')->required(),
            Forms\Components\TextInput::make('primary_cta_href')->required(),
            Forms\Components\TextInput::make('secondary_cta_label')->required(),
            Forms\Components\TextInput::make('secondary_cta_href')->required(),
            Forms\Components\SpatieMediaLibraryFileUpload::make('background')
                ->collection('background')
                ->image()
                ->maxSize(10240)
                ->required(false)
                ->helperText('Full-bleed background photo behind this slide\'s text. Max file size: 10 MB.'),
            Forms\Components\TextInput::make('background_alt')
                ->label('Background image alt text')
                ->helperText('Describes the photo for screen readers and search engines.'),
            Forms\Components\TextInput::make('order')
                ->numeric()
                ->default(0)
                ->helperText('Lowest value shows first and is the one rendered as the page\'s <h1>. Drag rows in the table below to reorder instead of hand-editing this.'),
            Forms\Components\Toggle::make('is_published')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('background')->collection('background'),
                Tables\Columns\TextColumn::make('eyebrow')->searchable(),
                Tables\Columns\TextColumn::make('headline')->limit(40),
                Tables\Columns\IconColumn::make('is_published')->boolean(),
            ])
            ->defaultSort('order')
            ->reorderable('order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHeroSlides::route('/'),
            'create' => Pages\CreateHeroSlide::route('/create'),
            'edit' => Pages\EditHeroSlide::route('/{record}/edit'),
        ];
    }
}
