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
 * separate settings page just for a two-field title. Also supports an
 * optional section-level image (e.g. Geographic Reach's infographic map) —
 * most sections won't use it, that's fine, it's optional.
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
        $record = SectionHeading::forKey($this->key, $this->defaultEyebrow, $this->defaultHeading);
        $this->form->model($record)->fill($record->only(['eyebrow', 'heading', 'image_alt']));
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('eyebrow')->required(),
            Forms\Components\TextInput::make('heading')->required(),
            Forms\Components\SpatieMediaLibraryFileUpload::make('image')
                ->collection('image')
                ->image()
                ->maxSize(10240)
                ->helperText('Optional section image (most sections don\'t need one). Max file size: 10 MB.')
                ->required(false),
            Forms\Components\TextInput::make('image_alt')
                ->label('Image alt text')
                ->helperText('Describes the image for screen readers and search engines.'),
        ])->statePath('data');
    }

    public function save(): void
    {
        $record = SectionHeading::forKey($this->key, $this->defaultEyebrow, $this->defaultHeading);

        $state = $this->form->getState();
        unset($state['image']);
        $record->update($state);

        $this->form->model($record)->saveRelationships();

        Notification::make()
            ->title('Section heading saved')
            ->success()
            ->send();
    }
}
