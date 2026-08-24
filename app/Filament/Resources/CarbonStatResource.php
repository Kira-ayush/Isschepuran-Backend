<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarbonStatResource\Pages;
use App\Models\CarbonStat;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class CarbonStatResource extends Resource
{
    protected static ?string $model = CarbonStat::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';
    protected static string|UnitEnum|null $navigationGroup = 'Impact Page';
    protected static ?string $navigationLabel = 'Carbon Mitigation';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('year')
                ->required()
                ->helperText('e.g. "2024"'),
            Forms\Components\TextInput::make('tons')
                ->required()
                ->numeric()
                ->helperText('Tons of CO2e sequestered/mitigated that year.'),
            Forms\Components\Toggle::make('is_projected')
                ->label('Projected (not yet final)'),
            Forms\Components\TextInput::make('order')->numeric()->default(0),
            Forms\Components\Toggle::make('is_published')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order')->sortable(),
                Tables\Columns\TextColumn::make('year'),
                Tables\Columns\TextColumn::make('tons'),
                Tables\Columns\IconColumn::make('is_projected')->boolean(),
                Tables\Columns\IconColumn::make('is_published')->boolean(),
            ])
            ->defaultSort('order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCarbonStats::route('/'),
            'create' => Pages\CreateCarbonStat::route('/create'),
            'edit' => Pages\EditCarbonStat::route('/{record}/edit'),
        ];
    }
}
