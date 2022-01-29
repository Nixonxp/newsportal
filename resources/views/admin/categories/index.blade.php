@extends('admin.layouts.master')
@section('title', __('admin.categories'))

@section('content')

<section class="content">
    <!-- Default box -->
    <nav class="navbar navbar-toggleable-md navbar-light bg-faded">
        <a class="btn btn-primary" href="{{ route('admin.categories.create') }}">@lang('admin.add_category')</a>
    </nav>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">@lang('admin.categories')</h3>

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
                    <th style="width: 20%">
                        @lang('admin.name')
                    </th>
                    <th style="width: 20%">
                        @lang('admin.created_at')
                    </th>
                    <th style="width: 20%">
                        @lang('admin.updated_at')
                    </th>
                    <th style="width: 20%">
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($categories as $category)
                    <tr>
                        <td>
                            {{ $category->id }}
                        </td>
                        <td>
                            <a href="{{ route('admin.categories.show', $category) }}">
                                {{ $category->name }}
                            </a>
                        </td>
                        <td>
                            {{ $category->created_at }}
                        </td>
                        <td>
                            @isset($category->updated_at)
                                {{ $category->updated_at }}
                            @else
                                -
                            @endisset
                        </td>
                        <td class="project-actions text-right">


                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST">
                                <a class="btn btn-info btn-sm" href="{{ route('admin.categories.edit', $category) }}">
                                    <i class="fas fa-pencil-alt">
                                    </i>
                                    @lang('admin.edit')
                                </a>
                                @csrf
                                @method('DELETE')
                                <input class="btn btn-danger" type="submit" value="@lang('admin.delete')">
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->

</section>

@endsection
