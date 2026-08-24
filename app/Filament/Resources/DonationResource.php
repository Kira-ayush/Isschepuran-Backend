<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DonationResource\Pages;
use App\Models\Donation;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

/**
 * Read-mostly view of donations that originate ONLY from the Razorpay
 * flow (DonationController::createOrder()) — never a manual admin entry,
 * hence canCreate() => false and no create page.
 */
class DonationResource extends Resource
{
    protected static ?string $model = Donation::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-heart';
    protected static string|UnitEnum|null $navigationGroup = 'Submissions';

    private const STATUS_COLORS = [
        'pending' => 'gray',
        'paid' => 'success',
        'failed' => 'danger',
        'refunded' => 'warning',
    ];

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('donor_name')->required(),
            Forms\Components\TextInput::make('donor_email')->email()->required(),
            Forms\Components\TextInput::make('donor_phone'),
            Forms\Components\TextInput::make('amount')->numeric()->required(),
            Forms\Components\TextInput::make('currency')->required(),
            Forms\Components\TextInput::make('razorpay_order_id')->disabled(),
            Forms\Components\TextInput::make('razorpay_payment_id')->disabled(),
            Forms\Components\Select::make('status')
                ->options([
                    'pending' => 'Pending', 'paid' => 'Paid',
                    'failed' => 'Failed', 'refunded' => 'Refunded',
                ])
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('donor_name')->searchable(),
                Tables\Columns\TextColumn::make('donor_email')->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->formatStateUsing(fn ($record) => $record->currency . ' ' . number_format((float) $record->amount, 2)),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => self::STATUS_COLORS[$state] ?? 'gray'),
                Tables\Columns\TextColumn::make('razorpay_order_id')->label('Order ID')->copyable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'pending' => 'Pending', 'paid' => 'Paid',
                    'failed' => 'Failed', 'refunded' => 'Refunded',
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDonations::route('/'),
            'edit' => Pages\EditDonation::route('/{record}/edit'),
        ];
    }
}
