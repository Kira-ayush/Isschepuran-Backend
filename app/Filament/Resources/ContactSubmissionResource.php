<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactSubmissionResource\Pages;
use App\Models\ContactSubmission;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class ContactSubmissionResource extends Resource
{
    protected static ?string $model = ContactSubmission::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-envelope';
    protected static string|UnitEnum|null $navigationGroup = 'Submissions';
    protected static ?string $navigationLabel = 'Contact Messages';

    private const STATUS_OPTIONS = ['new' => 'New', 'read' => 'Read', 'replied' => 'Replied'];

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('email')->email()->required(),
            Forms\Components\TextInput::make('subject'),
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
                Tables\Columns\TextColumn::make('subject')->limit(40),
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
            'index' => Pages\ListContactSubmissions::route('/'),
            'create' => Pages\CreateContactSubmission::route('/create'),
            'edit' => Pages\EditContactSubmission::route('/{record}/edit'),
        ];
    }
}
