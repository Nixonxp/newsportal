@extends('admin.layouts.master')
@section('title', __('admin.logs'))
@section('breadcrumbs', Breadcrumbs::view('partials.blocks.admin-breadcrumbs', \Request::route()->getName(), $log->id))

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
                            @php /** @var \App\Models\Log $log */  @endphp
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
                                    <td>{{ $log->id}}</td>
                                </tr>
                                <tr>
                                    <td>@lang('admin.importance')</td>
                                    <td>{!! __('admin.imp_label_' . $log->level_name) !!}</td>
                                </tr>
                                <tr>
                                    <td>@lang('admin.message')</td>
                                    <td>{{ $log->message }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                    <div class="text-muted">
                        <p class="text-sm">@lang('admin.created_at')
                            <b class="d-block">{{ $log->created_at }}</b>
                        </p>
                    </div>

                    <div class="text-center mt-5 mb-3">
                        <form action="{{ route('admin.logs.destroy', $log->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <input class="btn btn-danger btn-sm" type="submit" value="@lang('admin.delete')">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->

</section>

@endsection
