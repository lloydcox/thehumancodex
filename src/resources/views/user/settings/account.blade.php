@extends('layouts.user')

@section('main')
    <section class="main-container is-narrow">
        @component('user.settings.components.header')
            @slot('backUrl', route('settings'))
            @slot('title', 'Login')
        @endcomponent
        <section class="spacer is-large">
            <h5 class="section-name">Review your logins and set up your password.</h5>
            <thc-button-component url="{{ url('settings/account/email') }}">
                <template slot="name">
                    Email
                </template>
                <template slot="description">
                    {{ request()->user()->email }}
                </template>
            </thc-button-component>
            <thc-button-component url="{{ url('settings/account/password') }}">
                <template slot="name">
                    password
                </template>
            </thc-button-component>
        </section>
    </section>
@endsection