<div>
    <ul>
        @foreach ($navigation as $item)
            @if (isset($item['attributes']['type']))
                {{ (new $item['attributes']['type'])->render($item) }}
            @endif
        @endforeach
    </ul>
</div>
