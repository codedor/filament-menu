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

    <script>
        document.addEventListener('alpine:initializing', () => {
            window.Alpine.data('menuSortableContainer', ({ statePath }) => ({
                statePath,
                sortable: null,
                init() {
                    this.sortable = new Sortable(this.$el, {
                        group: 'nested',
                        animation: 150,
                        fallbackOnBody: true,
                        swapThreshold: 0.65,
                        draggable: '[data-sortable-item]',
                        handle: '[data-sortable-handle]',
                        onSort: () => {
                            this.sorted()
                        }
                    })
                },
                sorted() {
                    this.$wire.handleNewOrder(this.statePath, this.sortable.toArray())
                }
            }))
        })
    </script>
</x-filament::page>
