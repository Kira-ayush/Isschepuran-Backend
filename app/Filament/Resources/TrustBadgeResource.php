<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrustBadgeResource\Pages;
use App\Models\TrustBadge;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class TrustBadgeResource extends Resource
{
    protected static ?string $model = TrustBadge::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';
    protected static string|UnitEnum|null $navigationGroup = 'About Page';
    protected static ?string $navigationLabel = 'Trust Badges';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('name')
                ->required()
                ->helperText('e.g. "80G Certified"'),
            Forms\Components\TextInput::make('description')
                ->required()
                ->helperText('e.g. "Tax Exemption Benefits"'),
            Forms\Components\TextInput::make('order')->numeric()->default(0),
            Forms\Components\Toggle::make('is_published')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order')->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\IconColumn::make('is_published')->boolean(),
            ])
            ->defaultSort('order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrustBadges::route('/'),
            'create' => Pages\CreateTrustBadge::route('/create'),
            'edit' => Pages\EditTrustBadge::route('/{record}/edit'),
        ];
    }
}
