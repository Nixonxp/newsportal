<div class="gazette-catagory-posts-area">
    <div class="container">
        @isset($posts)
            <div class="row">
                <div class="col-12 col-md-12">
                    @foreach($posts as $post)
                        <x-news-card.inline :post="$post"></x-news-card.inline>
                    @endforeach
                </div>
            </div>
            {{ $posts->links('vendor.pagination.custom') }}
        @else
            <span>@lang('admin.empty_list')</span>
        @endisset
    </div>
</div>
