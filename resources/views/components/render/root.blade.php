<div>
    <ul>
        @foreach ($navigation as $item)
            <x-filament-menu::render.item :$item />
        @endforeach
    </ul>
</div>
