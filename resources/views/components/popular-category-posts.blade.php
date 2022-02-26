<div class="row">
    @foreach($posts as $post)
        <div class="col-12 col-md-{{ $loop->iteration > 3 ? 6 : 4 }}">
            <!-- Gazette Welcome Post -->
            <div class="gazette-welcome-post">
                <!-- Post Tag -->
                <div class="gazette-post-tag">
                    <a href="{{ route('category', $post->category) }}">{{ $post->category->name }}</a>
                </div>
                <h2 class="font-pt">{{ $post->title }}</h2>
                <p class="gazette-post-date">{{ $post->middleFormatDate }}</p>
                <!-- Post Thumbnail -->
                @if($post->getImageSrc() !== null)
                    <div class="blog-post-thumbnail my-5">
                        <img src="{{ $post->getImageSrc() }}" alt="{{ $post->title }}">
                    </div>
                @endif
                <!-- Post Excerpt -->
                <p>{!! $post->excerpt !!}</p>
                <!-- Reading More -->
                <div class="post-continue-reading-share mt-30">
                    <div class="post-continue-btn">
                        <a href="{{ route('newspost', [$post->category, $post]) }}" class="font-pt">@lang('main.continue_reading') <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
