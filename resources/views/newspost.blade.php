@extends('layouts.master')
@section('title', $post->title . __('admin.news'))
@section('meta_description', __('main.meta_post', ['post' => $post->title]))

@php /** @var \App\Models\Post $post */  @endphp

@section('content')

    <section class="single-post-area">
        <!-- Single Post Title -->
        <div class="single-post-title bg-img background-overlay" style="background-image: url({{ $post->getImageSrc() }});">
            <div class="container h-100">
                <div class="row h-100 align-items-end">
                    <div class="col-12">
                        <div class="single-post-title-content">
                            <!-- Post Tag -->
                            <div class="gazette-post-tag">
                                <a href="{{ route('category', $post->category) }}">{{ $post->category->name }}</a>
                            </div>
                            <h2 class="font-pt">{{ $post->title }}</h2>
                            <p>{{ $post->middleFormatDate }}</p>

                            <x-subscribe-button :post="$post"></x-subscribe-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="single-post-contents">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-8 single-post-text">
                        {{ $post->getContent() }}
                    </div>
                </div>
                @if($post->isExternal())
                    <div class="row">
                        <div class="col-12">
                            <div class="breacumb-content d-flex align-items-center justify-content-between align-left">
                                <p class="editorial-post-date text-dark my-3">
                                    <a href="{{ $post->source_link }}">@lang('admin.source') {{ $post->source_name }}</a>
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

@endsection('content')
