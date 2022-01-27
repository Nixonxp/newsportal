<div class="col-12 col-md-6">
    <div class="breaking-news-area">
        <h5 class="breaking-news-title">@lang('main.breaking_news')</h5>
        <div id="breakingNewsTicker" class="ticker">
            <ul>
                @foreach($lastPosts as $post)
                <li><a href="#">{{ $post->title }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
</div>


