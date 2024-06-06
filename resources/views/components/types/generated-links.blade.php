@foreach ($items as $item)
    <li>
        <a href="{!! $item->route() !!}">
            <span>{{ $item->title }}</span>
        </a>
    </li>
@endforeach
