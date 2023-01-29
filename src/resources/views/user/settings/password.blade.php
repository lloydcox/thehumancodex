@extends('layouts.user')

@section('main')
    <section class="main-container is-narrow">
        @component('user.settings.components.header')
            @slot('backUrl', url('settings/account'))
            @slot('title', 'Password')
        @endcomponent
        <settings-password-form/>
    </section>
@endsection