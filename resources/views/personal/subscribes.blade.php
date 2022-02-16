@extends('layouts.master')
@section('title', __('main.subscribe_authors'))
@section('h1', __('main.subscribe_authors'))
@section('personal_layout_head',view('partials.blocks.personal_layout_head'))
@section('personal_layout_footer',view('partials.blocks.personal_layout_footer'))

@section('content')
    <x-user-subscribe-list></x-user-subscribe-list>
@endsection
