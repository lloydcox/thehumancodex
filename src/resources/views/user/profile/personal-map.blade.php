@extends('layouts.user')

@section('mainClass', 'has-subnav-wrapper is-paddingless')

@section('main')
@if(Request::url() === URL::to('/').'/personal-map')
    <div class="profile-nav">
        <a href="{{route('horizontal-timeline')}}">
            <button class="btn btn-primary mb-3">chronological journey</button>
        </a>
        <a href="{{ url('profile') }}">
            <button class="btn btn-primary mb-3">Profile</button>
        </a>
        </div>
@endif
    <new-globe :posts="{{$data}}"></new-globe>
@endsection
