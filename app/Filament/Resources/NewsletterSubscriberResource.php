<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsletterSubscriberResource\Pages;
use App\Models\NewsletterSubscriber;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class NewsletterSubscriberResource extends Resource
{
    protected static ?string $model = NewsletterSubscriber::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-envelope-open';
    protected static string|UnitEnum|null $navigationGroup = 'Submissions';
    protected static ?string $navigationLabel = 'Newsletter Subscribers';

    private const STATUS_OPTIONS = ['subscribed' => 'Subscribed', 'unsubscribed' => 'Unsubscribed'];

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('email')->email()->required(),
            Forms\Components\Select::make('status')->options(self::STATUS_OPTIONS)->required()->default('subscribed'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\SelectColumn::make('status')->options(self::STATUS_OPTIONS),
                Tables\Columns\TextColumn::make('subscribed_at')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options(self::STATUS_OPTIONS),
            ])
            ->defaultSort('subscribed_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNewsletterSubscribers::route('/'),
            'create' => Pages\CreateNewsletterSubscriber::route('/create'),
            'edit' => Pages\EditNewsletterSubscriber::route('/{record}/edit'),
        ];
    }
}
