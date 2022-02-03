<!-- Latest News Marquee Area Start -->
<div class="latest-news-marquee-area">
    <div class="simple-marquee-container">
        <div class="marquee">
            <ul class="marquee-content-items">

                @foreach($lastPosts as $post)
                    <li>
                        <a href="{{ route('newspost', [$post->category, $post]) }}"><span class="latest-news-time">{{ $post->shortTimeFormat }}</span>{{ $post->title }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
<!-- Latest News Marquee Area End -->
