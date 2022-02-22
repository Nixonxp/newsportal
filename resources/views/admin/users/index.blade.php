@extends('admin.layouts.master')
@section('title', __('admin.users'))
@section('breadcrumbs', Breadcrumbs::view('partials.blocks.admin-breadcrumbs', \Request::route()->getName()))

@section('content')

<section class="content">
    <!-- Default box -->
    @can('create', App\Models\User::class)
        <nav class="navbar navbar-toggleable-md navbar-light bg-faded">
            <a class="btn btn-primary" id="add_record" href="{{ route('admin.users.create') }}">@lang('admin.add_record')</a>
        </nav>
    @endcan

    <div class="card card-info">
        <form action="{{ route('admin.users.index')}}" method="get" class="form-horizontal" >
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <!-- select -->
                        <div class="form-group">
                            <label for="role">@lang('admin.role')</label>
                            <select class="form-control custom-select" name="role" id="role">
                                <option value="">@lang('admin.all')</option>
                                @foreach($roles as $role)
                                    <option value="{{$role->id}}" @if(request()->query('role') === $role->id) selected @endif>
                                        {{$role->name}}
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
                                <option value="id_asc" @if(request()->query('sort') === 'id_asc') selected @endif>@lang('admin.id_asc')</option>
                                <option value="id_desc" @if(request()->query('sort') === 'id_desc') selected @endif>@lang('admin.id_desc')</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <!-- select -->
                        <div class="form-group">
                            <label for="name">@lang('admin.name')</label>
                            <input type="text" class="form-control" value="{{request()->name}}" name="name">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <!-- select -->
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" value="{{request()->email}}" name="email">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary float-right">@lang('admin.apply')</button>
                <a href="{{ route('admin.users.index')}}" class="btn btn-warning float-right mr-2">@lang('admin.reset')</a>
            </div>

        </form>
    </div>
@if ($users->isEmpty())
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
                <th style="width: 50%">
                    @lang('admin.name')
                </th>
                <th style="width: 10%">
                    Email
                </th>
                <th style="width: 20%">
                    @lang('admin.created_at')
                </th>
                <th style="width: 10%">
                    @lang('admin.updated_at')
                </th>
                <th style="width: 10%">
                    @lang('admin.role')
                </th>
                <th style="width: 20%">
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                @php /** @var \App\Models\User $user */  @endphp
                <tr>
                    <td>
                        {{ $user->id }}
                    </td>
                    <td>
                        @can('view', $user)
                            <a href="{{ route('admin.users.show', $user) }}">
                                {{ $user->name }}
                            </a>
                        @else
                            {{ $user->name }}
                        @endcan
                    </td>
                    <td>
                        {{ $user->email }}
                    </td>
                    <td>
                        {{ $user->created_at }}
                    </td>
                    <td>
                        @isset($user->updated_at)
                            {{ $user->updated_at }}
                        @else
                            -
                        @endisset
                    </td>
                    <td>
                        {{ $user->role->name }}
                    </td>
                    <td class="project-actions text-right">
                        @can('update', $user)
                            <a class="btn btn-info btn-sm" href="{{ route('admin.users.edit', $user) }}">
                                <i class="fas fa-pencil-alt">
                                </i>
                                @lang('admin.edit')
                            </a>
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="mt-2">
            {{ $users->withQueryString()->links() }}
        </div>
    </div>
        <!-- /.card-body -->
    </div>
    @endif

    <!-- /.card -->

</section>

@endsection
