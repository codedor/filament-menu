<x-filament::page>
    {{ $record->description }}

    <div x-data="{
        init () {
            let nestedSortables = document.querySelectorAll('.nested-sort')
            for (let i = 0; i < nestedSortables.length; i++) {
                new Sortable(nestedSortables[i], {
                    group: 'nested',
                    animation: 0,
                    fallbackOnBody: true,
                    swapThreshold: 0.65,
                    handle: '.nested-sort-handle',
                    onEnd (e) {
                        $wire.call('handleNewOrder',
                            e.item.dataset.id,
                            parseInt(e.to.dataset.id),
                        )
                    },
                })
            }
        },
        openEditModal (id) {
            $wire.call('setEditingMenuItem', id)
            $dispatch('open-modal', { id: 'filament-menu::edit-menu-item-modal' })
        },
        closeEditModal () {

        },
    }">
        <x-nested-menu-items
            :items="$record->items"
            :max-depth="$record->depth"
        />
    </div>

    <x-filament::button wire:click="createMenuItem">
        {{ __('filament-menu.add menu item') }}
    </x-filament::button>

    <x-filament::modal
        id="filament-menu::edit-menu-item-modal"
        x-on:modal-closed="closeEditModal()"
        width="4xl"
    >
        @if ($editingMenuItem)
            <x-slot name="header">
                <x-filament::modal.heading>
                    {{ __('filament-menu::edit-modal.editing :name', [
                        'name' => $editingMenuItem->working_title,
                    ]) }}
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

    <style>
        .nested-sort-placeholder .nested-sort-placeholder:after {
            content: ' ';
            display: block;
            margin: 10px 0 10px 3rem;
            min-height: 2rem;
            background-color: rgba(0, 0, 0, .05);
            border-radius: 5px;
        }
    </style>
</x-filament::page>
