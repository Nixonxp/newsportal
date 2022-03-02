@extends('layouts.master')
@section('title', __('admin.category') . ' ' . $category->name)
@section('meta_description', __('main.meta_category', ['category' => $category->name]))

@section('content')

@php /** @var \App\Models\Category $category */  @endphp

<!-- Breadcumb Area Start -->
<div class="breadcumb-area section_padding_50">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breacumb-content d-flex align-items-center justify-content-between">
                    <!-- Post Tag -->
                    <div class="gazette-post-tag">
                        <a href="javascript:void();">{{ $category->name }}</a>
                    </div>
                    <p class="editorial-post-date text-dark mb-0">{{ getCurrentDate() }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcumb Area End -->

<!-- Editorial Area Start -->
<x-popular-news-carousel :categoryId="$category->id"></x-popular-news-carousel>
<!-- Editorial Area End -->

<section class="catagory-welcome-post-area section_padding_100">
    <div class="container">
        <x-popular-category-posts :categoryId="$category->id"></x-popular-category-posts>

        <x-news-list-paginate :perPage="25" :category="$category->id"></x-news-list-paginate>
    </div>
</section>

@endsection('content')
