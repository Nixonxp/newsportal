<section class="gazatte-editorial-area section_padding_100 bg-dark">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="editorial-post-slides owl-carousel">
                    @foreach($posts as $post)
                        <x-news-card.owl-carousel-black :post="$post"></x-news-card.owl-carousel-black>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

