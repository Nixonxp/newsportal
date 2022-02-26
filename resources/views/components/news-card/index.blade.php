@php /** @var \App\Models\Post $mainPost */  @endphp
<!-- Gazette Welcome Post -->
<div class="gazette-welcome-post">
    <!-- Post Tag -->
    <div class="gazette-post-tag">
        <a href="{{ route('category', $mainPost->category) }}">{{ $mainPost->category->name }}</a>
    </div>
    <h2 class="font-pt">{{ $mainPost->title }}</h2>
    <p class="gazette-post-date">{{ $mainPost->middleFormatDate }}</p>
    <!-- Post Thumbnail -->
    <div class="blog-post-thumbnail my-5">
        <img src="{{ $mainPost->getImageSrc() }}" alt="post-thumb">
    </div>
    <!-- Post Excerpt -->
    <p>{!! $mainPost->excerpt !!}</p>
    <!-- Reading More -->
    <div class="post-continue-reading-share d-sm-flex align-items-center justify-content-between mt-30">
        <div class="post-continue-btn">
            <a href="{{ route('newspost', [$mainPost->category, $mainPost]) }}" class="font-pt">@lang('main.continue_reading') <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
        </div>
    </div>
</div>


