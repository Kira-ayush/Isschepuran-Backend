<?php

namespace App\Filament\Pages;

use App\Models\PaymentSetting;
use BackedEnum;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use UnitEnum;

/**
 * A singleton settings page for Razorpay credentials — entered here, not
 * .env, so a non-technical admin/client can self-serve their own
 * credentials without a developer redeploying. key_secret/webhook_secret
 * are encrypted at rest (see PaymentSetting's `encrypted` casts) and are
 * never exposed via any public API endpoint.
 */
class ManagePaymentSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'Payment Settings';
    protected static string|UnitEnum|null $navigationGroup = 'Get Involved Page';
    protected static ?string $slug = 'payment-settings';
    protected string $view = 'filament.pages.manage-payment-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(PaymentSetting::current()->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('key_id')
                ->label('Key ID')
                ->helperText('Razorpay\'s public Key ID (e.g. rzp_test_... or rzp_live_...).'),
            Forms\Components\TextInput::make('key_secret')
                ->label('Key Secret')
                ->password()
                ->revealable()
                ->helperText('Kept encrypted in the database, never shown on the public site.'),
            Forms\Components\TextInput::make('webhook_secret')
                ->label('Webhook Secret')
                ->password()
                ->revealable()
                ->helperText('Set this to the same secret configured on the Razorpay webhook (Settings → Webhooks) pointing at /api/v1/webhooks/razorpay.'),
            Forms\Components\Toggle::make('is_test_mode')
                ->label('Test mode')
                ->default(true)
                ->helperText('Informational only — shows a test-mode indicator on the donate form. Swap the fields above manually when moving to live keys.'),
        ])->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();
        PaymentSetting::current()->update($state);

        Notification::make()
            ->title('Payment settings saved')
            ->success()
            ->send();
    }
}
