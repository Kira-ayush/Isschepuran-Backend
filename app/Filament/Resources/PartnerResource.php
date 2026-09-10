<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartnerResource\Pages;
use App\Models\Partner;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

/**
 * The "Partners" logo wall shown on the Home and About pages (frontend
 * PartnersSection.tsx). `group` is a fixed 2-value Select (not a master
 * table) per this project's enum-vs-FK convention — the two rows are
 * structural, not a list that grows. Sits outside every page-specific nav
 * group because it renders on more than one page — same placement
 * reasoning as SiteSetting / CtaBand.
 *
 * Not the same as CsrPartner (the "Our CSR Partners" row inside the Impact
 * page's Corporate Social Synergy section).
 */
class PartnerResource extends Resource
{
    protected static ?string $model = Partner::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';
    protected static string|UnitEnum|null $navigationGroup = null;
    protected static ?string $navigationLabel = 'Partners';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('name')
                ->required()
                ->helperText('The partner organisation\'s name — also used as the logo\'s alt text if you leave that blank.'),
            Forms\Components\Select::make('group')
                ->options(Partner::GROUPS)
                ->default('partnership')
                ->required()
                ->helperText('Which row this logo appears in.'),
            Forms\Components\SpatieMediaLibraryFileUpload::make('logo')
                ->collection('logo')
                ->image()
                ->maxSize(10240)
                ->helperText('Only use a real, partner-supplied logo. Transparent PNG or SVG works best. Max file size: 10 MB.')
                ->required(false),
            Forms\Components\TextInput::make('logo_alt')
                ->label('Logo alt text')
                ->helperText('Describes the logo for screen readers. Defaults to the name above if left blank.'),
            Forms\Components\TextInput::make('order')
                ->numeric()
                ->default(0)
                ->helperText('Lowest shows first within its row. Drag rows in the table to reorder instead.'),
            Forms\Components\Toggle::make('is_published')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('logo')->collection('logo'),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('group')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Partner::GROUPS[$state] ?? $state),
                Tables\Columns\TextColumn::make('order')->sortable(),
                Tables\Columns\IconColumn::make('is_published')->boolean(),
            ])
            ->groups([
                Tables\Grouping\Group::make('group')
                    ->getTitleFromRecordUsing(fn (Partner $record): string => Partner::GROUPS[$record->group] ?? $record->group),
            ])
            ->defaultGroup('group')
            ->defaultSort('order')
            ->reorderable('order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPartners::route('/'),
            'create' => Pages\CreatePartner::route('/create'),
            'edit' => Pages\EditPartner::route('/{record}/edit'),
        ];
    }
}
