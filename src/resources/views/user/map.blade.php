{{-- feed map --}}

@extends('layouts.user')

@section('subNavigation')
    <div class="sub-navbar">
        @include('user.profile.components._switcher', [
            'class' => 'is-centered',
            'profileUrl' =>  url('/'),
            'mapUrl' => url('/map')
        ])
    </div>
@endsection

@section('mainClass', 'has-subnav-wrapper is-paddingless')

@section('main')
{{--    <timeline-map user-id="feed"></timeline-map>--}}
    <new-globe :posts="{{$data}}" :connections="{{$connections}}" :connection-categories="{{$connectionCategories}}"></new-globe>
@endsection
