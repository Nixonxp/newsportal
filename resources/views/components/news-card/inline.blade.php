@php /** @var \App\Models\Post $post */  @endphp

<div class="gazette-single-catagory-post">
    <h5><a href="{{ route('newspost', [$post->category, $post]) }}" class="font-pt">{{ $post->title }}</a></h5>
    <span>{{ $post->fullShortTimeFormat }}</span>
</div>



