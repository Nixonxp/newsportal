@extends('admin.layouts.master')
@section('title', __('admin.logs'))
{{--@section('breadcrumbs', Breadcrumbs::view('partials.blocks.admin-breadcrumbs', \Request::route()->getName()))--}}

@section('content')

<section class="content">
    <div class="card card-info">
        <form action="{{ route('admin.logs.index')}}" method="get" class="form-horizontal" >
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <!-- select -->
                        <div class="form-group">
                            <label for="type">@lang('admin.type')</label>
                            <select class="form-control custom-select" name="type" id="type">
                                <option value="">@lang('admin.all')</option>
                                <option value="CRITICAL" @if(request()->query('type') === 'CRITICAL') selected @endif>@lang('admin.important')</option>
                                <option value="WARNING" @if(request()->query('type') === 'WARNING') selected @endif>@lang('admin.warning')</option>
                                <option value="INFO" @if(request()->query('type') === 'INFO') selected @endif>@lang('admin.informational')</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary float-right">@lang('admin.apply')</button>
                <a href="{{ route('admin.subscribes.index')}}" class="btn btn-warning float-right mr-2">@lang('admin.reset')</a>
            </div>

        </form>
    </div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">@lang('admin.logs')</h3>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped projects">
                <thead>
                <tr>
                    <th style="width: 1%">
                        ID
                    </th>
                    <th style="width: 5%">
                        @lang('admin.importance')
                    </th>
                    <th style="width: 50%">
                        @lang('admin.message')
                    </th>
                    <th style="width: 20%">
                        @lang('admin.created_at')
                    </th>
                    <th style="width: 20%">
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($logs as $log)
                    <tr>
                        <td>
                            {{ $log->id }}
                        </td>
                        <td>
                            {!! __('admin.imp_label_' . $log->level_name) !!}
                        </td>
                        <td>
                            <a href="{{ route('admin.logs.show', $log) }}">
                                {{ Str::limit($log->message, 120)  }}
                            </a>
                        </td>
                        <td>
                            {{ $log->datetime }}
                        </td>
                        <td class="project-actions text-right">
                            <form action="{{ route('admin.logs.destroy', $log) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <input class="btn btn-danger btn-sm" type="submit" value="@lang('admin.delete')">
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="mt-2">
                {{ $logs->withQueryString()->links() }}
            </div>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->

</section>

@endsection
