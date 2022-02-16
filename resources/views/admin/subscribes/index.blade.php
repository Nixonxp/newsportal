@extends('admin.layouts.master')
@section('title', __('admin.subscribers'))
@section('breadcrumbs', Breadcrumbs::view('partials.blocks.admin-breadcrumbs', \Request::route()->getName()))

@section('content')

<section class="content">
    <!-- Default box -->

    <div class="card card-info">
        <form action="{{ route('admin.subscribes.index')}}" method="get" class="form-horizontal" >
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <!-- select -->
                        <div class="form-group">
                            <label for="author">@lang('admin.author')</label>
                            <select class="form-control" name="author" id="author">
                                <option value="">@lang('admin.all')</option>
                                @foreach($authors as $author)
                                    <option value="{{$author->id}}" @if(request()->query('author') == $author->id) selected @endif>{{$author->name}}</option>
                                @endforeach
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
@if ($subscribers->isEmpty())
    <div class="card card-info">
        <div class="row">
            <h2>Список пуст</h2>
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
                    @lang('admin.user_name')
                </th>
                <th style="width: 20%">
                    E-mail
                </th>
                <th style="width: 20%">
                    @lang('admin.author')
                </th>
                <th style="width: 10%">
                    @lang('admin.create_date')
                </th>
            </tr>
            </thead>
            <tbody>
            @php /** @var \App\Models\Subscriber $subscriber */  @endphp
            @foreach($subscribers as $subscriber)
                <tr>
                    <td>
                        {{ $subscriber->id }}
                    </td>
                    <td>
                        {{ $subscriber->user->name }}
                    </td>
                    <td>
                        {{ $subscriber->user->email }}
                    </td>
                    <td>
                        {{ $subscriber->author->name }}
                    </td>
                    <td>
                        {{ $subscriber->created_at }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="mt-2">
            {{ $subscribers->withQueryString()->links() }}
        </div>
    </div>
        <!-- /.card-body -->
    </div>
    @endif

    <!-- /.card -->

</section>

@endsection
