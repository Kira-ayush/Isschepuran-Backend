<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GeographicReachResource\Pages;
use App\Models\GeographicReach;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class GeographicReachResource extends Resource
{
    protected static ?string $model = GeographicReach::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-map-pin';
    protected static string|UnitEnum|null $navigationGroup = 'About Page';
    protected static ?string $navigationLabel = 'Geographic Reach';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('state')
                ->required()
                ->helperText('e.g. "West Bengal", "Jharkhand", "Odisha"'),
            Forms\Components\TextInput::make('region')
                ->required()
                ->helperText('e.g. "South 24 Parganas"'),
            Forms\Components\Textarea::make('description')
                ->required()
                ->rows(2)
                ->helperText('What activities happen here, e.g. "Cyclone relief, agroforestry, pond restoration."'),
            Forms\Components\TextInput::make('order')->numeric()->default(0),
            Forms\Components\Toggle::make('is_published')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order')->sortable(),
                Tables\Columns\TextColumn::make('state')->searchable(),
                Tables\Columns\TextColumn::make('region')->searchable(),
                Tables\Columns\TextColumn::make('description')->limit(60),
                Tables\Columns\IconColumn::make('is_published')->boolean(),
            ])
            ->defaultSort('order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGeographicReaches::route('/'),
            'create' => Pages\CreateGeographicReach::route('/create'),
            'edit' => Pages\EditGeographicReach::route('/{record}/edit'),
        ];
    }
}
