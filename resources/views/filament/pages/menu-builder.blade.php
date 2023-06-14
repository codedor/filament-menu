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

        <x-filament::button x-on:click="openEditModal()">
            {{ __('filament-menu.add menu item') }}
        </x-filament::button>
    </div>


    <x-filament::modal
        id="filament-menu::edit-menu-item-modal"
        width="4xl"
    >
        @if ($editingMenuItem)
            <x-slot name="header">
                <x-filament::modal.heading>
                    @if($editingMenuItem->id)
                        {{ __('filament-menu::edit-modal.editing :name', [
                            'name' => $editingMenuItem->working_title,
                        ]) }}
                    @else
                        {{ __('filament-menu::edit-modal.create new') }}
                    @endif
                </x-filament::modal.heading>
            </x-slot>

            <div class="py-8" wire:key="filament-menu::edit-modal.form" wire:loading.remove>
                {{ $this->form }}
            </div>

            <div
                class="w-full justify-center py-8"
                wire:key="filament-menu::edit-modal.loading"
                wire:loading.flex
            >
                <x-filament-support::loading-indicator class="w-10 h-10" />
            </div>

            <x-slot name="footer">
                <x-filament::modal.actions>
                    <x-filament::button color="secondary" x-on:click.prevent="close()">
                        {{ __('filament-menu::edit-modal.cancel') }}
                    </x-filament::button>

                    <x-filament::button x-on:click.prevent="$wire.submitEditForm()">
                        {{ __('filament-menu::edit-modal.confirm') }}
                    </x-filament::button>
                </x-filament::modal.actions>
            </x-slot>
        @else
            <div class="w-full flex justify-center py-8">
                <x-filament-support::loading-indicator class="w-10 h-10" />
            </div>
        @endif
    </x-filament::modal>
</x-filament::page>
