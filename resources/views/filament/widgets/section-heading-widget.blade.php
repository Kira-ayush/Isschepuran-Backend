<x-filament-widgets::widget>
    <x-filament::card>
        <h3 class="text-base font-semibold">{{ $label }}</h3>

        <form wire:submit="save" class="mt-4">
            {{ $this->form }}

            <div class="mt-4">
                <x-filament::button type="submit" size="sm">
                    Save
                </x-filament::button>
            </div>
        </form>
    </x-filament::card>
</x-filament-widgets::widget>
