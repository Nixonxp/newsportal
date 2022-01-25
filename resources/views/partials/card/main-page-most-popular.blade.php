@php /** @var \App\Models\Post $post */  @endphp

@foreach($lastPosts->take(2) as $post)
    <!-- Single Today Post -->
    <div class="gazette-single-todays-post d-md-flex align-items-start mb-50">
        <div class="todays-post-thumb">
            <img src="{{ Storage::url('images/' . $post->image) }}" alt="">
        </div>
        <div class="todays-post-content">
            <!-- Post Tag -->
            <div class="gazette-post-tag">
                <a href="#">{{ $post->category->name }}</a>
            </div>
            <h3><a href="#" class="font-pt mb-2">{{ $post->title }}</a></h3>
            <span class="gazette-post-date mb-2">{{ $post->middleFormatDate }}</span>
            {{--<a href="#" class="post-total-comments">3 Comments</a>--}}
            <p>{{ $mainPost->excerpt }}</p>
        </div>
    </div>
@endforeach


