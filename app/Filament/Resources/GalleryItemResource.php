<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GalleryItemResource\Pages;
use App\Models\GalleryItem;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

/**
 * `category` is a `GalleryCategory` master (FK), same admin-managed
 * add/remove/reorder pattern as Initiative's `Category` — see
 * GalleryCategoryResource. (This supersedes an earlier version of this
 * docblock that argued for a fixed 3-value Select instead; the user asked
 * for the master pattern directly, mirroring Initiative's.)
 */
class GalleryItemResource extends Resource
{
    protected static ?string $model = GalleryItem::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';
    protected static string|UnitEnum|null $navigationGroup = 'Gallery Page';
    protected static ?string $navigationLabel = 'Gallery Items';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\SpatieMediaLibraryFileUpload::make('image')
                ->collection('image')
                ->image()
                ->maxSize(10240)
                ->required()
                ->helperText('Max file size: 10 MB.'),
            Forms\Components\TextInput::make('image_alt')
                ->label('Image alt text'),
            Forms\Components\Textarea::make('caption')
                ->rows(2)
                ->required(false),
            Forms\Components\Select::make('category_id')
                ->label('Category')
                ->relationship('category', 'name')
                ->required()
                ->preload()
                ->helperText('Manage the list of categories under Categories in the nav.'),
            Forms\Components\TextInput::make('order')->numeric()->default(0),
            Forms\Components\Toggle::make('is_published')->default(true),
            Forms\Components\Toggle::make('is_featured')
                ->label('Featured image')
                ->helperText('Shown in the large banner above the gallery grid. Only one image can be featured — marking this one will automatically un-feature any other.'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')->collection('image'),
                Tables\Columns\TextColumn::make('caption')->limit(50),
                Tables\Columns\TextColumn::make('category.name')->badge(),
                Tables\Columns\TextColumn::make('order')->sortable(),
                Tables\Columns\IconColumn::make('is_featured')->boolean()->label('Featured'),
                Tables\Columns\IconColumn::make('is_published')->boolean(),
            ])
            ->defaultSort('order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGalleryItems::route('/'),
            'create' => Pages\CreateGalleryItem::route('/create'),
            'edit' => Pages\EditGalleryItem::route('/{record}/edit'),
        ];
    }
}
