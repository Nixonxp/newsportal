@extends('admin.layouts.master')
@php /** @var \App\Models\Post $post */  @endphp
@isset($post)
    @section('title', __('admin.edit_record', ['name' => $post->name]))
    @section('breadcrumbs', Breadcrumbs::view('partials.blocks.admin-breadcrumbs', \Request::route()->getName(), $post))
@else
    @section('title', __('admin.create_record'))
    @section('breadcrumbs', Breadcrumbs::view('partials.blocks.admin-breadcrumbs', \Request::route()->getName()))
@endisset

@section('content')

<section class="content">

    <div class="card card-primary">
        <!-- form start -->
            <form method="POST" enctype="multipart/form-data"
                  @isset($post)
                  action="{{ route('admin.posts.update', $post) }}"
                  @else
                  action="{{ route('admin.posts.store') }}"
                @endisset
            >
            <div class="card-body">
                @csrf
                @isset($post)
                    @method('PUT')
                @endisset
                <div class="form-group">
                    <label for="name">@lang('admin.title')</label>
                    <input type="text" class="form-control" id="title" name="title"
                           value="{{ old('title', isset($post) ? $post->title : null) }}">
                    @if($errors->has('title'))
                        <div class="alert alert-danger" role="alert">
                            {{ $errors->first('title') }}
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <label for="slug">@lang('admin.code')</label>
                    <input type="text" class="form-control" id="slug" name="slug"
                           value="{{ old('slug', isset($post) ? $post->slug : null) }}">
                    @if($errors->has('slug'))
                        <div class="alert alert-danger" role="alert">
                            {{ $errors->first('slug') }}
                        </div>
                    @endif
                </div>

                <div class="form-group row">
                    <label for="category_id" class="col-sm-2 col-form-label">@lang('admin.category'): </label>
                    <div class="col-sm-12">
                        <select name="category_id" id="category_id" class="form-control custom-select">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                        @isset($post)
                                        @if($post->category_id == $category->id)
                                        selected
                                    @endif
                                    @endisset
                                >{{ $category->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>@lang('admin.image')</label><br>
                    <img id='img-upload' src='{{ isset($post->image) ? Storage::url($post->image) : '' }}'/>
                    <div class="input-group">
                        <span class="input-group-btn">
                            <span class="btn btn-default btn-file btn-border">
                               {{ isset($post->image) ? __('admin.download_new_image') : __('admin.download_image') }}
                                <input type="file" class="custom-file-input" name="image" id="imgInp">
                            </span>
                        </span>
                        <input type="text" class="form-control" readonly>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">@lang('admin.description')</label><br>
                    <textarea rows="10" class="editor" name="excerpt">
                        {{ old('excerpt', isset($post) ? $post->excerpt : null) }}
                    </textarea>
                    @if($errors->has('excerpt'))
                        <div class="alert alert-danger" role="alert">
                            {{ $errors->first('excerpt') }}
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="content">@lang('admin.content')</label><br>
                    <textarea rows="20" class="editor" name="content">
                        {{ old('content', isset($post) ? $post->content : null) }}
                    </textarea>
                    @if($errors->has('content'))
                        <div class="alert alert-danger" role="alert">
                            {{ $errors->first('content') }}
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
