@extends('admin.layouts.master')
@section('title', __('admin.ads'))
@section('breadcrumbs', Breadcrumbs::view('partials.blocks.admin-breadcrumbs', \Request::route()->getName()))

@section('content')

<section class="content">
    <!-- Default box -->
    @can('create', App\Models\Ad::class)
        <nav class="navbar navbar-toggleable-md navbar-light bg-faded">
            <a class="btn btn-primary" id="add_record" href="{{ route('admin.ads.create') }}">@lang('admin.add_record')</a>
        </nav>
    @endcan

    <div class="card card-info">
        <form action="{{ route('admin.ads.index')}}" method="get" class="form-horizontal" >
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <!-- select -->
                        <div class="form-group">
                            <label for="type">@lang('admin.type')</label>
                            <select class="form-control custom-select" name="type" id="type">
                                <option value="">@lang('admin.all')</option>
                                @foreach($types as $type)
                                    <option value="{{$type}}" @if(request()->query('type') === $type) selected @endif>
                                        {{$type}}
                                    </option>
                                @endforeach
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
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <!-- select -->
                        <div class="form-group">
                            <label for="activity">@lang('admin.activity')</label>
                            <select class="form-control custom-select" name="activity" id="activity">
                                <option value="">@lang('admin.all')</option>
                                <option value="active" @if(request()->query('activity') === 'active') selected @endif>@lang('admin.active')</option>
                                <option value="noactive" @if(request()->query('activity') === 'noactive') selected @endif>@lang('admin.noactive')</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary float-right">@lang('admin.apply')</button>
                <a href="{{ route('admin.ads.index')}}" class="btn btn-warning float-right mr-2">@lang('admin.reset')</a>
            </div>

        </form>
    </div>
@if ($ads->isEmpty())
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
                <th style="width: 20%">
                    @lang('admin.name')
                </th>
                <th style="width: 10%">
                    @lang('admin.type')
                </th>
                <th style="width: 10%">
                    @lang('admin.image')
                </th>
                <th style="width: 20%">
                    @lang('admin.show_start_date')
                </th>
                <th style="width: 20%">
                    @lang('admin.show_end_date')
                </th>
                <th style="width: 10%">
                    @lang('admin.status')
                </th>
                <th style="width: 20%">
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($ads as $ad)
                @php /** @var \App\Models\Ad $ad */  @endphp
                <tr>
                    <td>
                        {{ $ad->id }}
                    </td>
                    <td>
                        @can('view', $ad)
                            <a href="{{ route('admin.ads.show', $ad) }}">
                                {{ $ad->name }}
                            </a>
                        @else
                            {{ $ad->name }}
                        @endcan
                    </td>
                    <td>
                        {{ $ad->type }}
                    </td>
                    <td>
                        @isset($ad->image)
                            <img width="50px" height="auto" src="{{ Storage::url($ad->image) }}" alt="">
                        @else
                            {!! __('admin.label_not_uploaded') !!}
                        @endisset
                    </td>
                    <td>
                        {{ $ad->showdate_start }}
                    </td>
                    <td>
                        {{ $ad->showdate_end }}
                    </td>
                    <td>
                        @if($ad->isActive())
                            <span class="badge bg-success">{{ __('admin.active') }}</span>
                        @else
                            <span class="badge bg-secondary">{{ __('admin.noactive') }}</span>
                        @endif
                    </td>
                    <td class="project-actions text-right">
                        @can('update', $ad)
                            <a class="btn btn-info btn-sm" href="{{ route('admin.ads.edit', $ad) }}">
                                <i class="fas fa-pencil-alt">
                                </i>
                                @lang('admin.edit')
                            </a>
                        @endcan
                            @can('delete', $ad)
                                <form action="{{ route('admin.ads.destroy', $ad) }}" method="POST">
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
            {{ $ads->withQueryString()->links() }}
        </div>
    </div>
        <!-- /.card-body -->
    </div>
    @endif

    <!-- /.card -->

</section>

@endsection
