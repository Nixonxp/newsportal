@php /** @var \App\Models\Post $post */  @endphp

@foreach($posts as $post)
    <!-- Single Today Post -->
    <div class="gazette-single-todays-post d-md-flex align-items-start mb-50">
        <div class="todays-post-thumb">
            <img src="{{ Storage::url($post->image) }}" alt="">
        </div>
        <div class="todays-post-content">
            <!-- Post Tag -->
            <div class="gazette-post-tag">
                <a href="{{ route('category', $post->category) }}">{{ $post->category->name }}</a>
            </div>
            <h3><a href="{{ route('newspost', [$post->category, $post]) }}" class="font-pt mb-2">{{ $post->title }}</a></h3>
            <span class="gazette-post-date mb-2">{{ $post->middleFormatDate }}</span>
            {{--<a href="#" class="post-total-comments">3 Comments</a>--}}
            <p>{{ $post->excerpt }}</p>
        </div>
    </div>
@endforeach


