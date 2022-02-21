@extends('layouts.master')
@section('title', 'Главная')

@section('content')

<x-index-main-slider></x-index-main-slider>

<x-latest-news-ribbon></x-latest-news-ribbon>


<!-- Main Content Area Start -->
<section class="main-content-wrapper section_padding_100">

    <div class="container">
        <div class="row">

            <x-popular-news-index></x-popular-news-index>

            <div class="col-12 col-lg-3 col-md-6">
                <div class="sidebar-area">

                    <x-news-widget></x-news-widget>

                    <x-ad-sidebar></x-ad-sidebar>

                </div>
            </div>
        </div>
    </div>
    <!-- Main Content Area End -->

    <!-- Catagory Posts Area Start -->
    <x-news-list-paginate :perPage="25"></x-news-list-paginate>
</section>
<!-- Catagory Posts Area End -->

<x-popular-news-carousel></x-popular-news-carousel>

@endsection('content')
