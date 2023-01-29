@extends('layouts.user')

@section('main')
    <section class="main-container">
        @if($cookieBanner)
            <cookie-banner url="{{ url('logout') }}"></cookie-banner>
        @endif
        @if(request()->user()->id == $user->id)
            <timeline-form :user="{{ $user }}" :post-categories="{{  $postCategories }}" post-url="{{ url('/post') }}"></timeline-form>
            <new-requests user-id="{{ $user->id }}"></new-requests>
        @endif
        <div class="spacer is-large">
            @include('user.profile.components._switcher', [
                    'profileUrl' =>  url('/'),
                    'mapUrl' => url('/map')
                ])
        </div>
            @if(request()->has('view_notification_post'))
                <feed post_id="{{request('view_notification_post')}}"></feed>
            @elseif(request()->has('view_category_posts') && request()->has('user_id'))
                <feed category_id="{{request('view_category_posts')}}" user_id="{{request('user_id')}}"></feed>
            @else
                <feed :post-categories="{{  $postCategories }}" :connection-categories="{{$connectionCategories}}"></feed>
            @endif

    </section>
@endsection
