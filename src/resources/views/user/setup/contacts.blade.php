@extends('layouts.user')

@section('main')
    <div class="main-container">
        <div class="card">
            <div class="card-content">
                <h2 class="page-title">Connect with your contacts already in The Human Codex</h2>
                <p>We’ll browse your contacts in address book to see if any of them are already with us.</p>
                <p>
                    <a href="{{ url('setup/contacts/search') }}" class="button is-primary is-rounded is-flat" v-loading>Connect with your family and friends</a>
                </p>
                <p class="is-size-7">We’ll need your permission to access contacts. But don’t worry - we don’t share your information with third-party and only use it to personalize your experience.</p>
            </div>
        </div>
        <div class="card">
            <div class="card-content">
                <h2 class="page-title">Invite Friends</h2>
                <p>Share the news of The Human Codex and invite your friends from other social networks.</p>
                <p>
                    <thc-copy-button
                            class="button is-rounded is-primary is-flat"
                            text="{{ $sharingLink }}"
                            message-on-copy="Share link copied in clipboard. Paste it or send it to your friends!">
                        Share
                    </thc-copy-button>
                </p>
            </div>
        </div>
        <div class="card is-transparent connections">
            <h5 class="is-size-7 has-text-grey-light spacer">People nearby</h5>
            @foreach($usersNearby as $u)
                <person-item :user="{{ $u }}"></person-item>
            @endforeach
        </div>
    </div>
@endsection