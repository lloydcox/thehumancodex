@extends('layouts.user')

@section('main')
    <div class="main-container">
        <div class="card">
            <div class="card-content">
                <h2 class="page-title">Connect with your contacts already in The Human Codex</h2>
                <p>We’ll browse your contacts in address book to see if any of them are already with us.</p>
                <form class="columns" action="{{ url('setup/contacts/results') }}">
                    <div class="column is-8 is-offset-2">
                        <thc-input name="email" value="{{$user->email}}" label="Email"></thc-input>
                        <button class="button is-primary is-rounded is-flat is-wide" v-loading>Next</button>
                    </div>
                </form>
                <p class="is-size-7">We’ll need your permission to access contacts. But don’t worry - we don’t share
                    your information with third-party and only use it to personalize your experience.</p>
                <p class="is-size-7 has-text-danger has-text-weight-bold">This is just an example of how it will work in
                    the future. We don't really use your email yet.</p>
            </div>
        </div>
    </div>
@endsection