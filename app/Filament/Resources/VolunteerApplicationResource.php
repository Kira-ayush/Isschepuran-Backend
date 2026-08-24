<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VolunteerApplicationResource\Pages;
use App\Models\VolunteerApplication;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class VolunteerApplicationResource extends Resource
{
    protected static ?string $model = VolunteerApplication::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-hand-raised';
    protected static string|UnitEnum|null $navigationGroup = 'Submissions';

    private const INTEREST_OPTIONS = [
        'reforestation' => 'Reforestation Projects',
        'waste_management' => 'Waste Management',
        'community_education' => 'Community Education',
        'administrative_support' => 'Administrative Support',
    ];

    private const STATUS_OPTIONS = [
        'new' => 'New', 'contacted' => 'Contacted', 'accepted' => 'Accepted', 'declined' => 'Declined',
    ];

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('email')->email()->required(),
            Forms\Components\TextInput::make('country_code')->required(),
            Forms\Components\TextInput::make('phone')->required(),
            Forms\Components\Select::make('area_of_interest')->options(self::INTEREST_OPTIONS)->required(),
            Forms\Components\Textarea::make('message')->rows(4)->required(),
            Forms\Components\Select::make('status')->options(self::STATUS_OPTIONS)->required()->default('new'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('area_of_interest')
                    ->formatStateUsing(fn (string $state): string => self::INTEREST_OPTIONS[$state] ?? $state),
                Tables\Columns\SelectColumn::make('status')->options(self::STATUS_OPTIONS),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options(self::STATUS_OPTIONS),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVolunteerApplications::route('/'),
            'create' => Pages\CreateVolunteerApplication::route('/create'),
            'edit' => Pages\EditVolunteerApplication::route('/{record}/edit'),
        ];
    }
}
