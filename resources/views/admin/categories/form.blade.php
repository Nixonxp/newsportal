@extends('admin.layouts.master')
@php /** @var \App\Models\Category $category */  @endphp
@isset($category)
    @section('title', __('admin.edit_category', ['name' => $category->name]))
@else
    @section('title', __('admin.create_category'))
@endisset

@section('content')

<section class="content">

    <div class="card card-primary">
        <!-- form start -->
            <form method="post"
                  @isset($category)
                  action="{{ route('admin.categories.update', $category) }}"
                  @else
                  action="{{ route('admin.categories.store') }}"
                @endisset
            >
            <div class="card-body">
                @csrf
                @isset($category)
                    @method('PUT')
                @endisset
                <div class="form-group">
                    <label for="name">@lang('admin.title')</label>
                    <input type="text" class="form-control" id="name" name="name"
                           value="{{ old('name', isset($category) ? $category->name : null) }}">
                    @if($errors->has('name'))
                        <div class="alert alert-danger" role="alert">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <label for="slug">@lang('admin.code')</label>
                    <input type="text" class="form-control" id="slug" name="slug"
                           value="{{ old('slug', isset($category) ? $category->slug : null) }}">
                    @if($errors->has('slug'))
                        <div class="alert alert-danger" role="alert">
                            {{ $errors->first('slug') }}
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="description">@lang('admin.description')</label><br>
                    <textarea rows="20" class="editor" name="description">
                        {{ old('description', isset($category) ? $category->description : null) }}
                    </textarea>
                    @if($errors->has('description'))
                        <div class="alert alert-danger" role="alert">
                            {{ $errors->first('description') }}
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
