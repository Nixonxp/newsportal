@extends('layouts.master')
@section('title', __('admin.category') . ' ' . $category->name)

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
                    <p class="editorial-post-date text-dark mb-0">{{ Helper::getCurrentDate() }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcumb Area End -->

<!-- Editorial Area Start -->
@php $posts = $categoryNews->take(5); @endphp
@include('partials.sections.owl-carousel-black', compact('posts'))
<!-- Editorial Area End -->

<section class="catagory-welcome-post-area section_padding_100">
    <div class="container">
        @include('partials.blocks.catagory-welcome-post-area', compact('posts'))

        <div class="row">
            <div class="col-12 col-md-12">
                <h4 class="block-heading">@lang('main.all_news')</h4>
                @foreach($categoryNewsPaginate as $post)
                    <div class="gazette-single-catagory-post">
                        <h5><a href="{{ route('newspost', [$post->category, $post]) }}" class="font-pt">{{ $post->title }}</a></h5>
                        <span>{{ $post->fullShortTimeFormat }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        {{ $categoryNewsPaginate->links('vendor.pagination.custom') }}
    </div>
</section>

@endsection('content')
