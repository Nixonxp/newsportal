@php /** @var \App\Models\Post $post */  @endphp

@foreach($posts as $post)
    <div class="single-dont-miss-post d-flex mb-30">
        <div class="dont-miss-post-thumb">
            <img src="{{ Storage::url($post->image) }}" alt="">
        </div>
        <div class="dont-miss-post-content">
            <a href="{{ route('newspost', [$post->category, $post]) }}" class="font-pt">{{ $post->title }}</a>
            <span>{{ $post->middleShortMonthFormatDate }}</span>
        </div>
    </div>
@endforeach



