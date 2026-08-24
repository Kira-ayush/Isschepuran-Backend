<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CsrPartnerResource\Pages;
use App\Models\CsrPartner;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class CsrPartnerResource extends Resource
{
    protected static ?string $model = CsrPartner::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';
    protected static string|UnitEnum|null $navigationGroup = 'Impact Page';
    protected static ?string $navigationLabel = 'CSR Partners';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\SpatieMediaLibraryFileUpload::make('logo')
                ->collection('logo')
                ->image()
                ->maxSize(10240)
                ->helperText(
                    'Only use a real, partner-supplied logo. Leave blank until real CSR '
                    . 'partners are confirmed with the client — do not use placeholder or '
                    . 'invented company names/logos here. Max file size: 10 MB.'
                )
                ->required(false),
            Forms\Components\TextInput::make('logo_alt')
                ->label('Logo alt text'),
            Forms\Components\TextInput::make('order')->numeric()->default(0),
            Forms\Components\Toggle::make('is_published')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('logo')->collection('logo'),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\IconColumn::make('is_published')->boolean(),
            ])
            ->defaultSort('order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCsrPartners::route('/'),
            'create' => Pages\CreateCsrPartner::route('/create'),
            'edit' => Pages\EditCsrPartner::route('/{record}/edit'),
        ];
    }
}
