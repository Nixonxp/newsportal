@extends('admin.layouts.master')
@php /** @var \App\Models\User $user */  @endphp
@isset($user)
    @section('title', __('admin.edit_record', ['name' => $user->name]))
    @section('breadcrumbs', Breadcrumbs::view('partials.blocks.admin-breadcrumbs', \Request::route()->getName(), $user))
@else
    @section('title', __('admin.create_record'))
    @section('breadcrumbs', Breadcrumbs::view('partials.blocks.admin-breadcrumbs', \Request::route()->getName()))
@endisset

@section('content')

<section class="content">
    <div class="card card-primary">
        <!-- form start -->
            <form method="POST" enctype="multipart/form-data"
                  @isset($user)
                  action="{{ route('admin.users.update', $user) }}"
                  @else
                  action="{{ route('admin.users.store') }}"
                @endisset
            >
            <div class="card-body">
                @csrf
                @isset($user)
                    @method('PUT')
                @endisset
                <div class="form-group">
                    <label for="name">@lang('admin.name')</label>
                    <input type="text" class="form-control" id="name" name="name"
                           value="{{ old('name', isset($user) ? $user->name : null) }}">
                    @if($errors->has('name'))
                        <div class="alert alert-danger" role="alert">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" id="email" name="email"
                           value="{{ old('email', isset($user) ? $user->email : null) }}">
                    @if($errors->has('email'))
                        <div class="alert alert-danger" role="alert">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="password">@lang('admin.password')</label>
                    <input type="password" class="form-control" id="password" name="password">
                    @if($errors->has('password'))
                        <div class="alert alert-danger" role="alert">
                            {{ $errors->first('password') }}
                        </div>
                    @endif
                </div>

                <div class="form-group row">
                    <label for="role_id" class="col-sm-2 col-form-label">@lang('admin.role'): </label>
                    <div class="col-sm-12">
                        <select name="role_id" id="role_id" class="form-control custom-select">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}"
                                        @isset($user)
                                        @if($user->role_id === $role->id)
                                        selected
                                    @endif
                                    @endisset
                                >{{ $role->name}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('role'))
                            <div class="alert alert-danger" role="alert">
                                {{ $errors->first('role') }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label>@lang('admin.image')</label><br>
                    <img id='img-upload' src='{{ isset($user->image) ? Storage::url($user->image) : '' }}'/>
                    <div class="input-group">
                        <span class="input-group-btn">
                            <span class="btn btn-default btn-file btn-border">
                               {{ isset($user->image) ? __('admin.download_new_image') : __('admin.download_image') }}
                                <input type="file" class="custom-file-input" name="image" id="imgInp">
                            </span>
                        </span>
                        <input type="text" class="form-control" readonly>
                    </div>
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
