<div class="col-12 col-lg-9">
    <x-news-card :mainPost="$popularPostsMain"></x-news-card>

    @if($popularPosts->isNotEmpty())
        <div class="gazette-todays-post section_padding_100_50">
            <div class="gazette-heading">
                <h4>@lang('main.todays_most_popular')</h4>
            </div>
            <x-news-card.medium :posts="$popularPosts"></x-news-card.medium>
        </div>
    @endif
</div>
