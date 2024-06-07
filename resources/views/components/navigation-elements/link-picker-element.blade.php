<li @class(['active' => $active])>
    <a href="{!! $link !!}">
        <span>{{ $label }}</span>
    </a>

    @if (count($children))
        <ul style="margin-left: 2rem">
            @foreach ($children as $item)
                @if (isset($item['attributes']['type']))
                    {{ (new $item['attributes']['type'])->render($item) }}
                @endif
            @endforeach
        </ul>
    @endif
</li>
