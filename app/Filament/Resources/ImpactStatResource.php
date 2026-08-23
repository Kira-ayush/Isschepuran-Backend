<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ImpactStatResource\Pages;
use App\Models\ImpactStat;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class ImpactStatResource extends Resource
{
    protected static ?string $model = ImpactStat::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';
    protected static string|UnitEnum|null $navigationGroup = 'Home Page';
    protected static ?string $navigationLabel = 'Impact Stats';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('label')
                ->required()
                ->helperText('e.g. "Trees Planted"'),
            Forms\Components\TextInput::make('value')
                ->required()
                ->numeric()
                ->helperText('The real number — this replaces the old site\'s hardcoded "0+" counters.'),
            Forms\Components\TextInput::make('suffix')
                ->maxLength(5)
                ->helperText('Optional, e.g. "+"'),
            Forms\Components\Select::make('icon')
                ->options([
                    'TreePine' => 'Tree', 'Sprout' => 'Sprout', 'Cloud' => 'Cloud',
                    'Wind' => 'Wind', 'Droplets' => 'Water drop', 'Users' => 'People',
                ])
                ->required(),
            Forms\Components\TextInput::make('order')
                ->numeric()
                ->default(0)
                ->helperText('Lower numbers show first (left to right).'),
            Forms\Components\Toggle::make('is_published')
                ->default(true)
                ->label('Show on site'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order')->sortable(),
                Tables\Columns\TextColumn::make('label')->searchable(),
                Tables\Columns\TextColumn::make('value')->formatStateUsing(
                    fn ($record) => number_format($record->value) . $record->suffix
                ),
                Tables\Columns\IconColumn::make('is_published')->boolean(),
            ])
            ->defaultSort('order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListImpactStats::route('/'),
            'create' => Pages\CreateImpactStat::route('/create'),
            'edit' => Pages\EditImpactStat::route('/{record}/edit'),
        ];
    }
}
