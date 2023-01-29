@extends('layouts.user')

@section('main')
    <section class="main-container is-narrow">
        @component('user.settings.components.header')
            @slot('backUrl', route('settings'))
            @slot('title', 'Edit profile')
        @endcomponent
        <settings-profile-form :post-categories="{{  $postCategories }}" ></settings-profile-form>
    </section>
@endsection
