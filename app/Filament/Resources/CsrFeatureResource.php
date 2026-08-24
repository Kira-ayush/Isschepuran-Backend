<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CsrFeatureResource\Pages;
use App\Models\CsrFeature;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class CsrFeatureResource extends Resource
{
    protected static ?string $model = CsrFeature::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-check-badge';
    protected static string|UnitEnum|null $navigationGroup = 'Impact Page';
    protected static ?string $navigationLabel = 'CSR Features';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('title')
                ->required()
                ->helperText('e.g. "100% Transparency"'),
            Forms\Components\Textarea::make('description')
                ->required()
                ->rows(3),
            Forms\Components\Select::make('icon')
                ->options([
                    'ShieldCheck' => 'Shield check', 'LayoutDashboard' => 'Dashboard',
                    'FileCheck2' => 'Report', 'Leaf' => 'Leaf',
                    'BarChart3' => 'Bar chart', 'Sparkles' => 'Sparkles',
                ])
                ->required(),
            Forms\Components\TextInput::make('order')->numeric()->default(0),
            Forms\Components\Toggle::make('is_published')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order')->sortable(),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('description')->limit(60),
                Tables\Columns\TextColumn::make('icon'),
                Tables\Columns\IconColumn::make('is_published')->boolean(),
            ])
            ->defaultSort('order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCsrFeatures::route('/'),
            'create' => Pages\CreateCsrFeature::route('/create'),
            'edit' => Pages\EditCsrFeature::route('/{record}/edit'),
        ];
    }
}
