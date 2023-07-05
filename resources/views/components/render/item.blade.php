<li @class(['active' => $item['active']])>
    <a href="{!! $item['url'] !!}">
        <span>{{ $item['title'] }}</span>
    </a>

    @if (count($item['children']))
        <ul style="margin-left: 2rem">
            @foreach ($item['children'] as $item)
                <x-filament-menu::render.item :$item />
            @endforeach
        </ul>
    @endif
</li>
