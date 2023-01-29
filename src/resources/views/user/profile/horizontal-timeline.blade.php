@extends('layouts.user')

@section('mainClass', 'has-subnav-wrapper is-paddingless')

@section('main')
    @if(Request::url() === URL::to('/').'/horizontal-timeline')
    <div class="profile-nav">
        <a href="{{route('personal-map')}}">
            <button class="btn btn-primary mb-3"> geographical journey</button>
        </a>
        <a href="{{ url('profile') }}">
            <button class="btn btn-primary mb-3">Profile</button>
        </a>
    </div>
        @endif
    <horizontal-timeline></horizontal-timeline> 
@endsection