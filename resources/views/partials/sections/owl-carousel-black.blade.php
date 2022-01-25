<section class="gazatte-editorial-area section_padding_100 bg-dark">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="editorial-post-slides owl-carousel">
                    @foreach($posts as $post)
                        @include('partials.card.owl-carousel-post-black', compact('posts'))
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
