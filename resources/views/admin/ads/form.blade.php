@extends('admin.layouts.master')
@php /** @var \App\Models\Ad $ad */  @endphp
@isset($ad)
    @section('title', __('admin.edit_record', ['name' => $ad->name]))
    @section('breadcrumbs', Breadcrumbs::view('partials.blocks.admin-breadcrumbs', \Request::route()->getName(), $ad))
@else
    @section('title', __('admin.create_record'))
    @section('breadcrumbs', Breadcrumbs::view('partials.blocks.admin-breadcrumbs', \Request::route()->getName()))
@endisset

@section('content')

<section class="content">
    <div class="card card-primary">
        <!-- form start -->
            <form method="POST" enctype="multipart/form-data"
                  @isset($ad)
                  action="{{ route('admin.ads.update', $ad) }}"
                  @else
                  action="{{ route('admin.ads.store') }}"
                @endisset
            >
            <div class="card-body">
                @csrf
                @isset($ad)
                    @method('PUT')
                @endisset
                <div class="form-group">
                    <label for="name">@lang('admin.name')</label>
                    <input type="text" class="form-control" id="name" name="name"
                           value="{{ old('name', isset($ad) ? $ad->name : null) }}">
                    @if($errors->has('name'))
                        <div class="alert alert-danger" role="alert">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <label for="link">@lang('admin.link')</label>
                    <input type="text" class="form-control" id="link" name="link"
                           value="{{ old('link', isset($ad) ? $ad->link : null) }}">
                    @if($errors->has('link'))
                        <div class="alert alert-danger" role="alert">
                            {{ $errors->first('link') }}
                        </div>
                    @endif
                </div>

                <div class="form-group row">
                    <label for="type" class="col-sm-2 col-form-label">@lang('admin.type'): </label>
                    <div class="col-sm-12">
                        <select name="type" id="type" class="form-control custom-select">
                            @foreach(\App\Models\Ad::TYPES as $type)
                                <option value="{{ $type }}"
                                        @isset($ad)
                                        @if($ad->type == $type)
                                        selected
                                    @endif
                                    @endisset
                                >{{ $type}}</option>
                            @endforeach
                        </select>
                    </div>
                    @if($errors->has('type'))
                        <div class="alert alert-danger" role="alert">
                            {{ $errors->first('type') }}
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label>@lang('admin.show_start_date'):</label>
                    <div class="input-group date" data-target-input="nearest">
                        <input type="text" id="showdate_start" @isset($ad) value="{{ old('showdate_start', isset($ad) ? $ad->showdate_start : null) }}" @endisset name="showdate_start" class="form-control datetimepicker-input" data-target="#showdate_start">
                        <div class="input-group-append" data-target="#showdate_start" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                    @if($errors->has('showdate_start'))
                        <div class="alert alert-danger" role="alert">
                            {{ $errors->first('showdate_start') }}
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label>@lang('admin.show_end_date'):</label>
                    <div class="input-group date" data-target-input="nearest">
                        <input type="text" id="showdate_end" @isset($ad) value="{{ old('showdate_end', isset($ad) ? $ad->showdate_end : null) }}" @endisset name="showdate_end" class="form-control datetimepicker-input" data-target="#showdate_end">
                        <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                    @if($errors->has('showdate_end'))
                        <div class="alert alert-danger" role="alert">
                            {{ $errors->first('showdate_end') }}
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label>@lang('admin.image')</label><br>
                    <img id='img-upload' src='{{ isset($ad->image) ? Storage::url($ad->image) : '' }}'/>
                    <div class="input-group">
                        <span class="input-group-btn">
                            <span class="btn btn-default btn-file btn-border">
                               {{ isset($ad->image) ? __('admin.download_new_image') : __('admin.download_image') }}
                                <input type="file" class="custom-file-input" name="image" id="imgInp">
                            </span>
                        </span>
                        <input type="text" class="form-control" readonly>
                    </div>
                    @if($errors->has('image'))
                        <div class="alert alert-danger" role="alert">
                            {{ $errors->first('image') }}
                        </div>
                    @endif
                </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">@lang('admin.save')</button>
            </div>
        </form>
    </div>

</section>

@endsection
