<div class="row">
    <div class="col-md-12 ml-3">
        @forelse($listAuthors as $author)
            <div class="gazette-single-todays-post d-md-flex align-items-start mb-50">
                @isset($author->image)
                    <div class="todays-post-thumb">
                        <img src="{{ Storage::url($author->image)}}" alt="">
                    </div>
                @endisset
                <div class="todays-post-content">
                    <!-- Post Tag -->
                    <h3><span class="font-pt mb-2">{{ $author->name }}</span></h3>
                    <span class="gazette-post-date mb-2">@lang('main.subscribe_date') {{ getMiddleFormatDateAttribute($author->created_at)}}</span>
                    <button type="button" id="unsubscribe_btn" data-author-id="{{ $author->author_id }}" class="btn btn-warning float-left">@lang('main.unsubscribe')</button>
                </div>
            </div>
        @empty
            <span>@lang('admin.empty_list_authors')</span>
        @endforelse
    </div>
</div>
