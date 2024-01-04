@props([
    'item',
    'statePath',
    'depth' => 0,
    'maxDepth',
])

<div
    x-data="{
        open: $persist(true),
    }"
    wire:key="{{ $statePath }}"
    data-id="{{ $statePath }}"
    class="space-y-2"
    data-sortable-item
    wire:key="{{ $statePath }}"
>
    <div @class([
        'relative group',
        'opacity-50' => $depth >= $maxDepth,
    ])>
        <div @class([
            'bg-white rounded-lg border border-gray-300 w-full flex menu-item',
            'dark:bg-gray-700 dark:border-gray-600' => config('filament.dark_mode'),
        ])>
            <button type="button" @class([
                'flex items-center bg-gray-50 rounded-l-lg border-r border-gray-300 p-2 cursor-grab focus:cursor-grabbing',
                'dark:bg-gray-800 dark:border-gray-600' => config('filament.dark_mode'),
            ]) data-sortable-handle>
                @svg('heroicon-o-ellipsis-vertical', 'text-gray-400 w-4 h-4 -mr-2')
                @svg('heroicon-o-ellipsis-vertical', 'text-gray-400 w-4 h-4')
            </button>

            <span class="py-2 px-4">
                <span>{{ $item->working_title }}</span>

                @foreach($item->getTranslations('online') as $locale => $online)
                    <span
                        @style([
                            match ($item->getTranslation('online', $locale)) {
                                false, null, '' => \Filament\Support\get_color_css_variables('danger', shades: [500, 700]),
                                default => \Filament\Support\get_color_css_variables('success', shades: [500, 700]),
                            }
                        ])
                        class="
                            text-custom-700 bg-custom-500/10 dark:text-custom-500
                            rtl:space-x-reverse min-h-6 px-2 py-0.5 text-sm font-medium tracking-tight
                            inline-flex items-center justify-center space-x-1
                            rounded-xl whitespace-nowrap
                        "
                    >
                        {{ $locale }}
                    </span>
                @endforeach

                @if(count($item->children) > 0)
                    <button
                        type="button"
                        x-on:click="open = !open"
                        title="Toggle children"
                        class="appearance-none text-gray-500"
                    >
                        <svg class="w-3.5 h-3.5 transition ease-in-out duration-200"
                            x-bind:class="{ '-rotate-90': !open }"
                            fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"
                        >
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                @endif
            </span>
        </div>

        <div @class([
            'menu-item--buttons absolute right-3 flex items-center',
            'dark:border-gray-600 dark:divide-gray-600' => config('filament.dark_mode'),
        ])>
            <x-filament-actions::actions :actions="[
                ($this->editAction)(['menuItem' => $item->id]),
                ($this->deleteAction)(['menuItem' => $item->id]),
            ]" />
        </div>
    </div>

    <div x-show="open" x-collapse class="menu-child-list">
        <div
            class="space-y-2"
            wire:key="{{ $statePath }}-children"
            x-data="menuSortableContainer({
                statePath: @js($statePath)
            })"
        >
            @foreach ($item->children as $child)
                <x-filament-menu::menu-item
                    :statePath="$statePath . '.children.' . $child->id"
                    :item="$child"
                    :depth="$depth + 1"
                    :maxDepth="$maxDepth"
                />
            @endforeach
        </div>
    </div>
</div>
