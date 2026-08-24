<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InitiativeResource\Pages;
use App\Models\Initiative;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class InitiativeResource extends Resource
{
    protected static ?string $model = Initiative::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-sparkles';
    protected static string|UnitEnum|null $navigationGroup = 'Home Page';
    protected static ?string $navigationLabel = 'Initiatives / Pillars';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('title')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(fn (Set $set, ?string $state) =>
                    $set('slug', Str::slug($state))
                ),
            Forms\Components\TextInput::make('slug')
                ->required()
                ->unique(ignoreRecord: true),
            Forms\Components\Select::make('category')
                ->options([
                    'environment' => 'Environment',
                    'water' => 'Water',
                    'community' => 'Community',
                ])
                ->required(),
            Forms\Components\Textarea::make('summary')
                ->required()
                ->rows(3)
                ->helperText('Short card description shown on the Home page and Initiatives listing.'),
            Forms\Components\RichEditor::make('body')
                ->helperText('Full detail shown on the Initiatives page (optional for now).'),
            Forms\Components\SpatieMediaLibraryFileUpload::make('image')
                ->collection('image')
                ->image()
                ->maxSize(10240)
                ->helperText('Real project photography — see the project doc\'s note on avoiding AI-generated "documentation" images. Max file size: 10 MB.'),
            Forms\Components\TextInput::make('icon')
                ->helperText('lucide-react icon name, e.g. "TreePine", "Droplets", "BookOpen"'),
            Forms\Components\Toggle::make('featured_on_home')
                ->label('Show in Home page Core Pillars (max 3 recommended)'),
            Forms\Components\TextInput::make('order')->numeric()->default(0),
            Forms\Components\Toggle::make('is_published')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')->collection('image'),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('category')->badge(),
                Tables\Columns\IconColumn::make('featured_on_home')->boolean()->label('On Home'),
                Tables\Columns\IconColumn::make('is_published')->boolean(),
            ])
            ->defaultSort('order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInitiatives::route('/'),
            'create' => Pages\CreateInitiative::route('/create'),
            'edit' => Pages\EditInitiative::route('/{record}/edit'),
        ];
    }
}
