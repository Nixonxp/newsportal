@php /** @var \App\Models\Post $post */  @endphp
<div class="single-blog-post-slide bg-img background-overlay-5" style="background-image: url({{ Storage::url('images/' . $post->image) }});">
    <!-- Single Blog Post Content -->
    <div class="single-blog-post-content">
        <div class="tags">
            <a href="#">{{ $post->category->name }}</a>
        </div>
        <h3><a href="#" class="font-pt">{{ $post->title }}</a></h3>
        <div class="date">
            <a href="javascript:void(0)">{{ $post->middleFormatDate }}</a>
        </div>
    </div>
</div>
