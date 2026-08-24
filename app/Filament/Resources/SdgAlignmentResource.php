<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SdgAlignmentResource\Pages;
use App\Models\SdgAlignment;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * Renders on both Home and Impact pages — deliberately has no
 * $navigationGroup, same reasoning as SiteSetting/CtaBand (see
 * backend/CLAUDE.md's "Site-wide" note).
 */
class SdgAlignmentResource extends Resource
{
    protected static ?string $model = SdgAlignment::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-globe-alt';
    protected static ?string $navigationLabel = 'SDG Alignment';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('sdg_number')
                ->label('SDG number')
                ->required()
                ->numeric()
                ->minValue(1)
                ->maxValue(17)
                ->helperText('e.g. 6 for "Clean Water & Sanitation"'),
            Forms\Components\TextInput::make('goal_name')
                ->required()
                ->helperText('e.g. "Clean Water & Sanitation"'),
            Forms\Components\Textarea::make('contribution_text')
                ->required()
                ->rows(3)
                ->helperText('This org\'s specific contribution toward this goal.'),
            Forms\Components\TextInput::make('order')->numeric()->default(0),
            Forms\Components\Toggle::make('is_published')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order')->sortable(),
                Tables\Columns\TextColumn::make('sdg_number')->label('SDG'),
                Tables\Columns\TextColumn::make('goal_name')->searchable(),
                Tables\Columns\TextColumn::make('contribution_text')->limit(60),
                Tables\Columns\IconColumn::make('is_published')->boolean(),
            ])
            ->defaultSort('order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSdgAlignments::route('/'),
            'create' => Pages\CreateSdgAlignment::route('/create'),
            'edit' => Pages\EditSdgAlignment::route('/{record}/edit'),
        ];
    }
}
