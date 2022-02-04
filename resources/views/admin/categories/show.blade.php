@extends('admin.layouts.master')
@section('title', __('admin.categories'))
@section('breadcrumbs', Breadcrumbs::view('partials.blocks.admin-breadcrumbs', \Request::route()->getName(), $category))

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
                            @php /** @var \App\Models\Category $category */  @endphp
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
                                    <td>{{ $category->id}}</td>
                                </tr>
                                <tr>
                                    <td>@lang('admin.code')</td>
                                    <td>{{ $category->slug }}</td>
                                </tr>
                                <tr>
                                    <td>@lang('admin.title')</td>
                                    <td>{{ $category->name }}</td>
                                </tr>
                                <tr>
                                    <td>@lang('admin.description')</td>
                                    <td>{{ $category->getDescription() }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                    <div class="text-muted">
                        <p class="text-sm">@lang('admin.created_at')
                            <b class="d-block">{{ $category->created_at }}</b>
                        </p>
                        <p class="text-sm">@lang('admin.updated_at')
                            <b class="d-block">
                            @isset($category->updated_at)
                                    {{ $category->updated_at }}
                                @else
                                    -
                            @endisset
                            </b>
                        </p>
                    </div>

                    <div class="text-center mt-5 mb-3">
                        @can('update', $category)
                            <a class="btn btn-info btn-sm" href="{{ route('admin.categories.edit', $category) }}">
                                <i class="fas fa-pencil-alt">
                                </i>
                                @lang('admin.edit')
                            </a>
                        @endcan

                        @can('delete', $category)
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST">
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
