@php /** @var \App\Models\Post $lastPosts */  @endphp

@foreach($lastPosts->take(3) as $post)
    <div class="single-dont-miss-post d-flex mb-30">
        <div class="dont-miss-post-thumb">
            <img src="{{ Storage::url('images/' . $mainPost->image) }}" alt="">
        </div>
        <div class="dont-miss-post-content">
            <a href="#" class="font-pt">{{ $post->title }}</a>
            <span>{{ $post->middleShortMonthFormatDate }}</span>
        </div>
    </div>
@endforeach



