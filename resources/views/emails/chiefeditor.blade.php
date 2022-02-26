<h2>Добрый день!</h2>
<p>
    {{ $data->message }}
</p>
@forelse($data->links as $linkItem)
    <a href="{{ $linkItem->href }}">{{ $linkItem->title }}</a>
@empty
@endforelse
