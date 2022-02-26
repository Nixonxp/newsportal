@extends('admin.layouts.master')
@section('title', __('admin.newsposts'))
@section('breadcrumbs', Breadcrumbs::view('partials.blocks.admin-breadcrumbs', \Request::route()->getName(), $post))

@section('content')

<section class="content">

    <!-- Default box -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">@lang('admin.element_detail')</h3>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="card-body" style="display: block;">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                    <div class="row">
                        <div class="col-12">
                            <h4>@lang('admin.main_data')</h4>
                            @php /** @var \App\Models\Post $post */  @endphp
                            <table class="table">
                                <tbody>
                                <tr>
                                    <th>
                                        @lang('admin.field')
                                    </th>
                                    <th>
                                        @lang('admin.value')
                                    </th>
                                </tr>
                                <tr>
                                    <td>ID</td>
                                    <td>{{ $post->id}}</td>
                                </tr>
                                <tr>
                                    <td>@lang('admin.title')</td>
                                    <td>{{ $post->title }}</td>
                                </tr>
                                <tr>
                                    <td>@lang('admin.code')</td>
                                    <td>{{ $post->slug }}</td>
                                </tr>
                                <tr>
                                    <td>@lang('admin.category')</td>
                                    <td>{{ $post->category->name }}</td>
                                </tr>
                                <tr>
                                    <td>@lang('admin.show_popular')</td>
                                    <td>{!! $post->isPopular() ? __('admin.label_true') : __('admin.label_false') !!}</td>
                                </tr>
                                <tr>
                                    <td>@lang('admin.show_in_main_slider')</td>
                                    <td>{!! $post->isShowInMainSlider() ? __('admin.label_true') : __('admin.label_false') !!}</td>
                                </tr>
                                <tr>
                                    <td>@lang('admin.image')</td>
                                    <td>
                                        @if($post->getImageSrc() !== null)
                                            <img width="250px" height="250px" src="{{ $post->getImageSrc() }}" alt="">
                                        @else
                                            {!! __('admin.label_not_uploaded') !!}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>@lang('admin.excerpt')</td>
                                    <td>{{ $post->getExcerpt() }}</td>
                                </tr>
                                <tr>
                                    <td>@lang('admin.text')</td>
                                    <td>{{ $post->getContent() }}</td>
                                </tr>

                                @if($post->isExternal())
                                    <tr>
                                        <td>@lang('admin.news_source_name')</td>
                                        <td>{{ $post->source_name }}</td>
                                    </tr>

                                    <tr>
                                        <td>@lang('admin.news_source_link')</td>
                                        <td>{{ $post->source_link }}</td>
                                    </tr>
                                @endif

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                    <div class="text-muted">
                        <p class="text-sm">@lang('admin.created_at')
                            <b class="d-block">{{ $post->created_at }}</b>
                        </p>
                        <p class="text-sm">@lang('admin.updated_at')
                            <b class="d-block">
                            @isset($post->updated_at)
                                    {{ $post->updated_at }}
                                @else
                                    -
                            @endisset
                            </b>
                        </p>
                        <p class="text-sm">@lang('admin.author')
                            <b class="d-block">
                                @isset($post->user_id)
                                    {{ $post->user->name }}
                                @else
                                    {!! __('admin.label_not_setted') !!}
                                @endisset
                            </b>
                        </p>
                        <p class="text-sm">@lang('admin.status')
                            <b class="d-block">
                                <span class="badge badge-{{ $post->getStatusAttribute(true)->class }}">
                                    {{ __('admin.' . $post->getStatusAttribute(true)->value) }}
                                </span>
                            </b>
                        </p>
                        @if($post->isExternal())
                            <p class="text-sm">@lang('admin.is_external')
                                <b class="d-block">
                                    {!! __('admin.label_true') !!}
                                </b>
                            </p>

                        @endif
                    </div>

                    <div class="text-center mt-5 mb-3">
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
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->

</section>

@endsection
