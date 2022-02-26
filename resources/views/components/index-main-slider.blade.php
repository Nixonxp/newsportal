<!-- Welcome Blog Slide Area Start -->
<section class="welcome-blog-post-slide owl-carousel">
    <!-- Single Blog Post -->
    @foreach($posts as $post)
        @php /** @var \App\Models\Post $post */  @endphp
        <div class="single-blog-post-slide bg-img background-overlay-5" style="background-image: url({{ $post->getImageSrc() }});">
            <!-- Single Blog Post Content -->
            <div class="single-blog-post-content">
                <div class="tags">
                    <a href="{{ route('category', $post->category) }}">{{ $post->category->name }}</a>
                </div>
                <h3><a href="{{ route('newspost', [$post->category, $post]) }}" class="font-pt">{{ $post->title }}</a></h3>
                <div class="date">
                    <a href="javascript:void(0)">{{ $post->middleFormatDate }}</a>
                </div>
            </div>
        </div>
    @endforeach
</section>
<!-- Welcome Blog Slide Area End -->
