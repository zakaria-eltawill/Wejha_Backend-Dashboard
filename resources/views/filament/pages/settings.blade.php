<x-filament-panels::page>
    <form wire:submit="save" class="space-y-6">
        {{ $this->form }}

        <div class="flex justify-end">
            <x-filament::button type="submit" size="md">
                {{ __('filament-pages.settings.save_changes_button') }}
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
