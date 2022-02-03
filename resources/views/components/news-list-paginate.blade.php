<div class="gazette-catagory-posts-area">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-12">
                <h4 class="block-heading">@lang('main.all_news')</h4>
                @foreach($posts as $post)
                    <x-news-card.inline :post="$post"></x-news-card.inline>
                @endforeach
            </div>
        </div>
        {{ $posts->links('vendor.pagination.custom') }}
    </div>
</div>
