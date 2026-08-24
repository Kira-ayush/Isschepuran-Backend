<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DonationMethodResource\Pages;
use App\Models\DonationMethod;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

/**
 * Static, admin-managed donation details (bank/UPI/international) —
 * distinct from the Razorpay online-payment integration (see
 * PaymentSetting/DonationResource). `fields` is a flexible label->value
 * map since each type needs different fields.
 */
class DonationMethodResource extends Resource
{
    protected static ?string $model = DonationMethod::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';
    protected static string|UnitEnum|null $navigationGroup = 'Get Involved Page';
    protected static ?string $navigationLabel = 'Donation Methods';

    private const TYPE_OPTIONS = [
        'bank' => 'Bank Transfer',
        'upi' => 'UPI',
        'international' => 'International',
    ];

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\Select::make('type')
                ->options(self::TYPE_OPTIONS)
                ->required(),
            Forms\Components\TextInput::make('title')
                ->required()
                ->helperText('e.g. "Bank Transfer", "UPI"'),
            Forms\Components\KeyValue::make('fields')
                ->label('Details')
                ->keyLabel('Label')
                ->valueLabel('Value')
                ->helperText('e.g. "Account Name" -> "Ichhe Puran Trust", "IFSC" -> "NGRB0001234"'),
            Forms\Components\SpatieMediaLibraryFileUpload::make('qr_image')
                ->label('QR code image')
                ->collection('qr_image')
                ->image()
                ->maxSize(10240)
                ->required(false)
                ->helperText('Only relevant for the UPI method. Max file size: 10 MB.'),
            Forms\Components\TextInput::make('qr_image_alt')
                ->label('QR code alt text'),
            Forms\Components\Textarea::make('instructions')
                ->rows(2)
                ->required(false),
            Forms\Components\TextInput::make('order')->numeric()->default(0),
            Forms\Components\Toggle::make('is_published')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order')->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => self::TYPE_OPTIONS[$state] ?? $state),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\IconColumn::make('is_published')->boolean(),
            ])
            ->defaultSort('order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDonationMethods::route('/'),
            'create' => Pages\CreateDonationMethod::route('/create'),
            'edit' => Pages\EditDonationMethod::route('/{record}/edit'),
        ];
    }
}
