<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AboutMilestoneResource\Pages;
use App\Models\AboutMilestone;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class AboutMilestoneResource extends Resource
{
    protected static ?string $model = AboutMilestone::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-flag';
    protected static string|UnitEnum|null $navigationGroup = 'About Page';
    protected static ?string $navigationLabel = 'Journey / Milestones';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('year')
                ->required()
                ->helperText('e.g. "2021"'),
            Forms\Components\TextInput::make('title')
                ->required()
                ->helperText('e.g. "Foundation in Crisis"'),
            Forms\Components\Textarea::make('description')
                ->required()
                ->rows(3),
            Forms\Components\SpatieMediaLibraryFileUpload::make('image')
                ->collection('image')
                ->image()
                ->maxSize(10240)
                ->helperText('Optional photo for this milestone. Max file size: 10 MB.')
                ->required(false),
            Forms\Components\TextInput::make('order')->numeric()->default(0),
            Forms\Components\Toggle::make('is_published')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')->collection('image'),
                Tables\Columns\TextColumn::make('order')->sortable(),
                Tables\Columns\TextColumn::make('year')->searchable(),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('description')->limit(60),
                Tables\Columns\IconColumn::make('is_published')->boolean(),
            ])
            ->defaultSort('order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAboutMilestones::route('/'),
            'create' => Pages\CreateAboutMilestone::route('/create'),
            'edit' => Pages\EditAboutMilestone::route('/{record}/edit'),
        ];
    }
}
