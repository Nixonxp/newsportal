@extends('admin.layouts.master')
@section('title', __('admin.ad_detail'))
@section('breadcrumbs', Breadcrumbs::view('partials.blocks.admin-breadcrumbs', \Request::route()->getName(), $ad))

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
                            @php /** @var \App\Models\Ad $ad */  @endphp
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
                                    <td>{{ $ad->id}}</td>
                                </tr>
                                <tr>
                                    <td>@lang('admin.name')</td>
                                    <td>{{ $ad->name }}</td>
                                </tr>
                                <tr>
                                    <td>@lang('admin.type')</td>
                                    <td>{{ $ad->type }}</td>
                                </tr>
                                <tr>
                                    <td>@lang('admin.link')</td>
                                    <td>{{ $ad->link }}</td>
                                </tr>
                                <tr>
                                    <td>@lang('admin.show_start_date')</td>
                                    <td>{{ $ad->showdate_start }}</td>
                                </tr>
                                <tr>
                                    <td>@lang('admin.show_end_date')</td>
                                    <td>{{ $ad->showdate_end }}</td>
                                </tr>
                                <tr>
                                    <td>@lang('admin.image')</td>
                                    <td>
                                        @isset($ad->image)
                                            <img width="350px" height="auto" src="{{ Storage::url($ad->image) }}" alt="">
                                        @else
                                            {!! __('admin.label_not_uploaded') !!}
                                        @endisset
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                    <div class="text-muted">
                        <p class="text-sm">@lang('admin.created_at')
                            <b class="d-block">{{ $ad->created_at }}</b>
                        </p>
                        <p class="text-sm">@lang('admin.updated_at')
                            <b class="d-block">
                            @isset($ad->updated_at)
                                    {{ $ad->updated_at }}
                                @else
                                    -
                            @endisset
                            </b>
                        </p>
                        <p class="text-sm">@lang('admin.status')
                            <b class="d-block">
                            @if($ad->isActive())
                                <span class="badge bg-success">{{ __('admin.active') }}</span>
                            @else
                                <span class="badge bg-secondary">{{ __('admin.noactive') }}</span>
                            @endif
                            </b>
                        </p>
                    </div>

                    <div class="text-center mt-5 mb-3">
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
                                <input class="btn btn-danger btn-sm" id="delete" type="submit" value="@lang('admin.delete')">
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
