@extends('layouts.master')
@section('title', 'Главная')

@section('content')
<!-- Welcome Blog Slide Area Start -->
<section class="welcome-blog-post-slide owl-carousel">
    <!-- Single Blog Post -->
    @foreach($posts as $post)
        @include('partials.card.owl-carousel-post', compact('post'))
    @endforeach
</section>
<!-- Welcome Blog Slide Area End -->

<!-- Latest News Marquee Area Start -->
<div class="latest-news-marquee-area">
    <div class="simple-marquee-container">
        <div class="marquee">
            <ul class="marquee-content-items">

                @foreach($lastPosts->take(5) as $post)
                    <li>
                        <a href="#"><span class="latest-news-time">{{ $post->shortTimeFormat }}</span>{{ $post->title }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
<!-- Latest News Marquee Area End -->

<!-- Main Content Area Start -->
<section class="main-content-wrapper section_padding_100">

    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-9">
                @include('partials.card.main-page-post', compact('mainPost'))

                <div class="gazette-todays-post section_padding_100_50">
                    <div class="gazette-heading">
                        <h4>@lang('main.todays_most_popular')</h4>
                    </div>
                    @include('partials.card.main-page-most-popular', compact('lastPosts'))
                </div>

            </div>

            <div class="col-12 col-lg-3 col-md-6">
                <div class="sidebar-area">
                    <!-- Breaking News Widget -->
                    <div class="breaking-news-widget">
                        <div class="widget-title">
                            <h5>@lang('main.breaking_news')</h5>
                        </div>
                        @include('partials.card.main-page-breaking-news-sidebar-widget', compact('lastPosts'))
                    </div>

                    <!-- Don't Miss Widget -->
                    <div class="donnot-miss-widget">
                        <div class="widget-title">
                            <h5>@lang('main.dont_miss')</h5>
                        </div>
                        @include('partials.card.main-page-dont-miss-sidebar-widget', compact('lastPosts'))
                    </div>
                    <!-- Advert Widget -->
                    <div class="advert-widget">
                        <div class="widget-title">
                            <h5>@lang('main.advert')</h5>
                        </div>
                        <div class="advert-thumb mb-30">
                            <a href="#"><img src="img/add.png" alt=""></a>
                        </div>
                    </div>
                    <!-- Subscribe Widget -->
                    <div class="subscribe-widget">
                        <div class="widget-title">
                            <h5>@lang('main.subscribe')</h5>
                        </div>
                        <div class="subscribe-form">
                            <form action="#">
                                <input type="email" name="email" id="subs_email" placeholder="@lang('main.your_email')">
                                <button type="submit">@lang('main.to_subscribe')</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Main Content Area End -->

    <!-- Catagory Posts Area Start -->
    <div class="gazette-catagory-posts-area">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-12">
                    <h4 class="block-heading">@lang('main.all_news')</h4>
                    @foreach($posts as $post)
                        <div class="gazette-single-catagory-post">
                            <h5><a href="#" class="font-pt">{{ $post->title }}</a></h5>
                            <span>{{ $post->fullShortTimeFormat }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Catagory Posts Area End -->


@include('partials.sections.owl-carousel-black', compact('posts'))

@endsection('content')
