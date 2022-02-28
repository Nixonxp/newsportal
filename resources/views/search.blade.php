@extends('layouts.master')
@section('title', __('admin.search'))

@section('content')
    <div class="container py-5">
        <div class="card">
            <div class="card-header">
                @lang('admin.newsposts') <small>({{ $posts->count() }})</small>
            </div>
            <div class="card-body">
                <form action="{{ url('search') }}" method="get">
                    <div class="form-group">
                        <input
                            type="text"
                            name="q"
                            class="form-control"
                            placeholder="@lang('main.search_placeholder')"
                            value="{{ request('q') }}"
                        />
                    </div>
                </form>
                @forelse ($posts as $post)
                    <article class="mb-3">
                        <h3>
                            <a href="{{ route('newspost', [$post->category, $post]) }}">
                                {{ $post->title }}
                            </a>
                        </h3>
                        <p class="m-0">{!! Str::limit($post->content, 150) !!}</p>
                    </article>
                @empty
                    <p>@lang('admin.empty_list')</p>
                @endforelse
            </div>
        </div>
    </div>

@endsection('content')
