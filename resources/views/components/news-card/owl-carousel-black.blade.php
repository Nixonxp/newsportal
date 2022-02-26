@php /** @var \App\Models\Post $post */  @endphp
<div class="editorial-post-single-slide">
    <div class="row">
        <div class="col-12 col-md-5">
            @if($post->getImageSrc() !== null)
                <div class="editorial-post-thumb">
                    <img src="{{ $post->getImageSrc() }}" alt="{{ $post->title }}">
                </div>
            @endif
        </div>
        <div class="col-12 col-md-7">
            <div class="editorial-post-content">
                <!-- Post Tag -->
                <div class="gazette-post-tag">
                    <a href="{{ route('category', $post->category) }}">{{ $post->category->name }}</a>
                </div>
                <h2><a href="{{ route('newspost', [$post->category, $post]) }}" class="font-pt mb-15">{{ $post->title }}</a></h2>
                <p class="editorial-post-date mb-15">{{ $post->middleFormatDate }}</p>
                <p>{!! $post->excerpt !!}</p>
            </div>
        </div>
    </div>
</div>
