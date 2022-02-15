@php /** @var \App\Models\Post $post */  @endphp
@isset($post->user)
    <div class="row article-author">
        @isset($post->user->image)
            <div class="col-md-1">
                <div class="comment-author">
                    <img src="{{ Storage::url($post->user->image) }}" alt="">
                </div>
            </div>
        @endisset
        <div class="col-md-5 ml-4">
            <h5>{{ $post->user->name }}</h5>
            @auth
                @if($isSubscribed)
                    <button type="button" class="btn btn-primary float-left" disabled>@lang('main.subscribed')</button>
                @else
                    <button type="button" id="subscribe_btn" data-post-id="{{ $post->id }}" class="btn btn-primary float-left">@lang('main.to_subscribe')</button>
                @endif
            @endauth
        </div>
    </div>
@endisset
