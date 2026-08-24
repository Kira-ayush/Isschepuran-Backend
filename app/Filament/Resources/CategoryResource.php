<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

/**
 * The "category master" for Initiatives — admins add/remove/reorder
 * categories here instead of a fixed enum baked into code. `color` is
 * restricted to the approved design-token palette (see COLOR_OPTIONS)
 * rather than a free picker, so new categories stay on-brand.
 */
class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-tag';
    protected static string|UnitEnum|null $navigationGroup = 'Initiatives Page';
    protected static ?string $navigationLabel = 'Categories';

    private const COLOR_OPTIONS = [
        'forest' => 'Forest green',
        'sage' => 'Sage',
        'mustard' => 'Mustard',
        'forest-dark' => 'Forest dark',
    ];

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('name')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(fn (Set $set, ?string $state) =>
                    $set('slug', Str::slug($state))
                ),
            Forms\Components\TextInput::make('slug')
                ->required()
                ->unique(ignoreRecord: true)
                ->helperText('Used as the API identifier — changing this after initiatives are assigned is safe, they follow the same row.'),
            Forms\Components\Select::make('color')
                ->label('Badge color')
                ->options(self::COLOR_OPTIONS)
                ->required()
                ->default('forest')
                ->helperText('Restricted to the site\'s approved palette — keeps new categories on-brand.'),
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
                Tables\Columns\TextColumn::make('color')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => self::COLOR_OPTIONS[$state] ?? $state),
                Tables\Columns\TextColumn::make('initiatives_count')
                    ->counts('initiatives')
                    ->label('Initiatives'),
                Tables\Columns\IconColumn::make('is_published')->boolean(),
            ])
            ->defaultSort('order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
