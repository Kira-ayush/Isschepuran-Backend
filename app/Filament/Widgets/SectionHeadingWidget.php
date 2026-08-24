<?php

namespace App\Filament\Widgets;

use App\Models\SectionHeading;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Widgets\Widget;

/**
 * Reusable "eyebrow + heading" editor shown above a Resource's list table
 * (via getHeaderWidgets()), for sections that are a collection of records
 * (Pillars, Testimonials) rather than a true singleton — so there's no
 * separate settings page just for a two-field title.
 */
class SectionHeadingWidget extends Widget implements HasSchemas
{
    use InteractsWithSchemas;

    // Not a general-purpose Dashboard widget — only ever used explicitly via
    // ::make(['key' => ..., ...]) on a specific Resource's list page. Without
    // this, Filament's auto-discovery (app/Filament/Widgets/ is scanned by
    // AdminPanelProvider's discoverWidgets()) shows it on the Dashboard too,
    // instantiated with no properties — $key is then accessed uninitialized.
    protected static bool $isDiscovered = false;

    protected string $view = 'filament.widgets.section-heading-widget';

    public string $key;

    public string $defaultEyebrow = '';

    public string $defaultHeading = '';

    public string $label = 'Section Heading';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(
            SectionHeading::forKey($this->key, $this->defaultEyebrow, $this->defaultHeading)
                ->only(['eyebrow', 'heading'])
        );
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('eyebrow')->required(),
            Forms\Components\TextInput::make('heading')->required(),
        ])->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();
        SectionHeading::forKey($this->key, $this->defaultEyebrow, $this->defaultHeading)
            ->update($state);

        Notification::make()
            ->title('Section heading saved')
            ->success()
            ->send();
    }
}
