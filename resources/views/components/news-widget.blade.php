<!-- Breaking News Widget -->
@if($lastPostsWeek->isNotEmpty())
    <div class="breaking-news-widget">
        <div class="widget-title">
            <h5>@lang('main.breaking_news')</h5>
        </div>
        <x-news-card.sidebar-small :posts="$lastPostsWeek"></x-news-card.sidebar-small>
    </div>
@endif
<!-- Don't Miss Widget -->
@if($lastPostsMonth->isNotEmpty())
    <div class="donnot-miss-widget">
        <div class="widget-title">
            <h5>@lang('main.dont_miss')</h5>
        </div>
        <x-news-card.sidebar-inline :posts="$lastPostsMonth"></x-news-card.sidebar-inline>
    </div>
@endif
