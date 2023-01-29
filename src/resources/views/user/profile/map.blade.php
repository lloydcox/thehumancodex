<!-- This is the view to see another users personal map  -->







@extends('layouts.user')

@section('subNavigation')
    <div class="sub-navbar">
        @if(request()->user()->id === $user->id)
            @include('user.profile.components._switcher', [
                'class' => 'is-centered',
                'profileUrl' =>  url('profile'),
                'mapUrl' => url('profile/map')
            ])
        @else
            @include('user.profile.components._switcher', [
                'class' => 'is-centered',
                'profileUrl' =>  url("codex/{$user->username}"),
                'mapUrl' => url("codex/{$user->username}/map")
            ])
        @endif
    </div>
@endsection

@section('mainClass', 'has-subnav-wrapper is-paddingless')

@section('main')
    <timeline-map user-id="{{ $user->id }}"></timeline-map>
    
   
@endsection

@push('headScripts')
    {{--<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_API_KEY') }}"--}}
            {{--type="text/javascript"></script>--}}
@endpush