@extends('admin.layouts.master')
@section('title', __('admin.news'))
@section('breadcrumbs', Breadcrumbs::view('partials.blocks.admin-breadcrumbs', \Request::route()->getName()))

@section('content')

<section class="content">
    <!-- Default box -->
    @can('create', App\Models\Post::class)
        <nav class="navbar navbar-toggleable-md navbar-light bg-faded">
            <a class="btn btn-primary" href="{{ route('admin.posts.create') }}">@lang('admin.add_record')</a>
        </nav>
    @endcan

    <div class="card card-info">
        <form action="{{ route('admin.posts.index')}}" method="get" class="form-horizontal" >
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <!-- select -->
                        <div class="form-group">
                            <label for="status">@lang('admin.status')</label>
                            <select class="form-control custom-select" name="status" id="status">
                                <option value="">@lang('admin.all')</option>
                                <option value="publicated" @if(request()->query('status') === 'publicated') selected @endif>@lang('admin.published')</option>
                                <option value="draft" @if(request()->query('status') === 'draft') selected @endif>@lang('admin.draft')</option>
                                <option value="deleted" @if(request()->query('status') === 'deleted') selected @endif>@lang('admin.deleted')</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <!-- select -->
                        <div class="form-group">
                            <label for="sort">@lang('admin.sort')</label>
                            <select class="form-control custom-select" name="sort" id="sort">
                                <option value="">@lang('admin.default')</option>
                                <option value="new" @if(request()->query('sort') === 'new') selected @endif>@lang('admin.first_new')</option>
                                <option value="old" @if(request()->query('sort') === 'old') selected @endif>@lang('admin.first_old')</option>
                                <option value="updated_new" @if(request()->query('sort') === 'updated_new') selected @endif>@lang('admin.first_new_updates')</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary float-right">@lang('admin.apply')</button>
                <a href="{{ route('admin.posts.index')}}" class="btn btn-warning float-right mr-2">@lang('admin.reset')</a>
            </div>

        </form>
    </div>
@if ($posts->total() < 1)
    <div class="card card-info">
        <div class="row">
            <h2>@lang('admin.empty_list')</h2>
        </div>
    </div>
@else
    <div class="card">
        <div class="card-body p-0">
        <table class="table table-striped projects">
            <thead>
            <tr>
                <th style="width: 1%">
                    ID
                </th>
                <th style="width: 50%">
                    @lang('admin.name')
                </th>
                <th style="width: 10%">
                    @lang('admin.image')
                </th>
                <th style="width: 20%">
                    @lang('admin.published_at')
                </th>
                <th style="width: 10%">
                    @lang('admin.updated_at')
                </th>
                <th style="width: 10%">
                    @lang('admin.status')
                </th>
                <th style="width: 20%">
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($posts as $post)
                @php /** @var \App\Models\Post $post */  @endphp
                <tr>
                    <td>
                        {{ $post->id }}
                    </td>
                    <td>
                        @can('view', $post)
                            <a href="{{ route('admin.posts.show', $post) }}">
                                {{ $post->title }}
                            </a>
                        @else
                            {{ $post->title }}
                        @endcan
                        <br>
                        <small>
                            @lang('admin.created_at') {{ $post->created_at }}
                        </small>
                    </td>
                    <td>
                        <img width="50px" height="50px" src="{{ Storage::url($post->image) }}" alt="">
                    </td>
                    <td>
                        @isset($post->published_at)
                            {{ $post->published_at }}
                        @else
                            -
                        @endisset
                    </td>
                    <td>
                        @isset($post->updated_at)
                            {{ $post->updated_at }}
                        @else
                            -
                        @endisset
                    </td>
                    <td>
                    <span class="badge badge-{{ $post->getStatusAttribute(true)->class }}">
                        {{ __('admin.' . $post->getStatusAttribute(true)->value) }}
                    </span>
                    </td>
                    <td class="project-actions text-right">

                        @can('update', $post)
                            <a class="btn btn-info btn-sm" href="{{ route('admin.posts.edit', $post) }}">
                                <i class="fas fa-pencil-alt">
                                </i>
                                @lang('admin.edit')
                            </a>
                        @endcan
                        @can('delete', $post)
                            <form action="{{ route('admin.posts.destroy', $post) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <input class="btn btn-danger btn-sm" type="submit" value="@lang('admin.delete')">
                            </form>
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="mt-2">
            {{ $posts->withQueryString()->links() }}
        </div>
    </div>
        <!-- /.card-body -->
    </div>
    @endif

    <!-- /.card -->

</section>

@endsection
