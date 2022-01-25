@php /** @var \App\Models\Post $post */  @endphp
<div class="editorial-post-single-slide">
    <div class="row">
        <div class="col-12 col-md-5">
            <div class="editorial-post-thumb">
                <img src="{{ Storage::url('images/' . $post->image) }}" alt="">
            </div>
        </div>
        <div class="col-12 col-md-7">
            <div class="editorial-post-content">
                <!-- Post Tag -->
                <div class="gazette-post-tag">
                    <a href="#">{{ $post->category->name }}</a>
                </div>
                <h2><a href="#" class="font-pt mb-15">{{ $post->title }}</a></h2>
                <p class="editorial-post-date mb-15">{{ $post->middleFormatDate }}</p>
                <p>{{ $post->excerpt }}</p>
            </div>
        </div>
    </div>
</div>
