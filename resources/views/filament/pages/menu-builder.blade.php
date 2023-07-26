<x-filament::page>
    {{ $record->description }}

    <div
        class="filament-menu"
        x-data="menuSortableContainer({
            statePath: 'data.items'
        })"
        data-menu-container
    >
        @foreach($record->items as $item)
            <x-filament-menu::menu-item
                :item="$item"
                statePath="data.items.{{ $item->id }}"
                :maxDepth="$record->depth"
            />
        @endforeach

        {{ $this->addAction }}
    </div>
</x-filament::page>
