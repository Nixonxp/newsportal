@php /** @var \App\Models\Post $mainPost */  @endphp
<!-- Gazette Welcome Post -->
<div class="gazette-welcome-post">
    <!-- Post Tag -->
    <div class="gazette-post-tag">
        <a href="#">{{ $mainPost->category->name }}</a>
    </div>
    <h2 class="font-pt">{{ $mainPost->title }}</h2>
    <p class="gazette-post-date">{{ $mainPost->middleFormatDate }}</p>
    <!-- Post Thumbnail -->
    <div class="blog-post-thumbnail my-5">
        <img src="{{ Storage::url($mainPost->image) }}" alt="post-thumb">
    </div>
    <!-- Post Excerpt -->
    <p>{{ $mainPost->excerpt }}</p>
    <!-- Reading More -->
    <div class="post-continue-reading-share d-sm-flex align-items-center justify-content-between mt-30">
        <div class="post-continue-btn">
            <a href="#" class="font-pt">@lang('main.continue_reading') <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
        </div>
        <div class="post-share-btn-group">
            <a href="#"><i class="fa fa-pinterest" aria-hidden="true"></i></a>
            <a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
            <a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a>
            <a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a>
        </div>
    </div>
</div>


