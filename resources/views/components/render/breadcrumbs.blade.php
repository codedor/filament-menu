<ul style="display: flex">
    @foreach ($breadcrumbs as $crumb)
        <li>
            <a href="{!! $crumb['url'] !!}">
                <span>{{ $crumb['title'] }}</span>
            </a>

            @if (! $loop->last)
                >
            @endif
        </li>
    @endforeach
</ul>
