<x-filament::page>
    {{ $record->description }}

    @if ($record->hasItemsWithDepthIssues())
        <p
            style="{{ Filament\Support\get_color_css_variables('danger', [200, 400, 500, 600]) }}"
            class="py-4 px-6 flex gap-4 items-center bg-white text-custom-500 border rounded-lg"
        >
            @svg('heroicon-o-exclamation-circle', 'text-custom-500 w-8 h-8')
            {{ __('filament-menu::menu-builder.depth issue warning') }}
        </p>
    @endif

    <div
        class="filament-menu"
        x-data="menuSortableContainer({ statePath: 'data.items' })"
        data-menu-container
    >
        @foreach($record->items as $item)
            <x-filament-menu::menu-item
                state-path="data.items.{{ $item->id }}"
                :max-depth="$record->depth"
                :item="$item"
            />
        @endforeach
    </div>

    <div wire:key="filament-menu-actions">
        {{ $this->addAction }}
    </div>
</x-filament::page>
