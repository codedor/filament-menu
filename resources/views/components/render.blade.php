<div>
    <ul>
        @foreach ($navigation as $item)
            <x-filament-menu::menu-item :$item />
        @endforeach
    </ul>
</div>
