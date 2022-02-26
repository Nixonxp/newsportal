@php /** @var \App\Models\Post $post */  @endphp

@foreach($posts as $post)
    <div class="single-breaking-news-widget">
        @if($post->getImageSrc() !== null)
            <img src="{{ $post->getImageSrc() }}" alt="{{ $post->title }}">
        @endif
        <div class="breakingnews-title">
            <p>{{ $post->category->name }}</p>
        </div>
        <div class="breaking-news-heading gradient-background-overlay">
            <h5 class="font-pt">{{ $post->title }}</h5>
        </div>
    </div>
@endforeach



