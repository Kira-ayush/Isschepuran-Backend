<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CsrInquiryResource\Pages;
use App\Models\CsrInquiry;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class CsrInquiryResource extends Resource
{
    protected static ?string $model = CsrInquiry::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-office';
    protected static string|UnitEnum|null $navigationGroup = 'Submissions';
    protected static ?string $navigationLabel = 'CSR Inquiries';

    private const BUDGET_OPTIONS = [
        '5l_10l' => '₹5L – ₹10L',
        '10l_50l' => '₹10L – ₹50L',
        '50l_plus' => '₹50L+',
    ];

    private const STATUS_OPTIONS = [
        'new' => 'New', 'contacted' => 'Contacted', 'accepted' => 'Accepted', 'declined' => 'Declined',
    ];

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('organization_name')->required(),
            Forms\Components\TextInput::make('contact_person')->required(),
            Forms\Components\TextInput::make('email')->email()->required(),
            Forms\Components\TextInput::make('country_code')->required(),
            Forms\Components\TextInput::make('phone')->required(),
            Forms\Components\Select::make('budget_range')->options(self::BUDGET_OPTIONS)->required(),
            Forms\Components\Textarea::make('goals')->rows(4)->required(),
            Forms\Components\Select::make('status')->options(self::STATUS_OPTIONS)->required()->default('new'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('organization_name')->searchable(),
                Tables\Columns\TextColumn::make('contact_person')->searchable(),
                Tables\Columns\TextColumn::make('budget_range')
                    ->formatStateUsing(fn (string $state): string => self::BUDGET_OPTIONS[$state] ?? $state),
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
            'index' => Pages\ListCsrInquiries::route('/'),
            'create' => Pages\CreateCsrInquiry::route('/create'),
            'edit' => Pages\EditCsrInquiry::route('/{record}/edit'),
        ];
    }
}
