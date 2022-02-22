@extends('admin.layouts.master')
@section('title', __('admin.admin_space_panel'))

@section('content')

<div class="container-fluid">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <x-admin-news-widget></x-admin-news-widget>
        <!-- ./col -->
        <x-admin-ad-widget></x-admin-ad-widget>
        <!-- ./col -->
        <x-admin-new-user-widget></x-admin-new-user-widget>
        <!-- ./col -->
        <x-admin-log-widget></x-admin-log-widget>
        <!-- ./col -->
    </div>
    <!-- /.row -->
</div><!-- /.container-fluid -->

@endsection
