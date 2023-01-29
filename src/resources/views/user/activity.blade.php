@extends('layouts.user')

@section('main')
    
    <section class="main-container">
        <new-requests user-id="{{ Auth::id() }}"></new-requests>
        <h5 class="is-size-7 has-text-grey-light">Activities: {{ $notifications->count() }}
            @if($notifications && $notifications->count() > 1)
            <a href="{{'/activities?mark_all_as_read=true'}}" class="-pull-right" style="margin-left: 20px"> <i class="fa fa-check-circle"></i> Mark all as read!</a>
            @endif
        </h5>
        <div class="spacer">
            @if(!empty($notifications))
            @foreach($notifications as $notification)
                <div class="media">
                    <div class="media-left">
                        <figure class="image is-40x40">
                            <a href="{{ route('codex', $notification->causer->username) }}">
                                <img src="{{ $notification->causer->avatar }}" alt="Avatar" class="is-rounded">
                            </a>
                        </figure>
                    </div>
                    <div class="media-content">
                        <h5 class="title is-size-7 has-font-sans">
                            <a href="{{ route('codex', $notification->causer->username) }}" class="has-text-primary">{{ $notification->causer->fullName }}</a>
                            {!! $notification->content !!}
                        </h5>
                        <p class="subtitle is-size-7 has-text-grey-light">
                            {{ $notification->created_at->format('F d') }}
                        </p>

                    </div>
                    <div class="media-right">
                        <a href="{{'/activities?mark_as_read='.$notification->id}}" data-toggle="tooltip" data-placement="right" title="Mark as read!"><i class="fa fa-check-circle"></i></a>
                    </div>
                </div>
            @endforeach
            @endif
        </div>
    </section>
@endsection