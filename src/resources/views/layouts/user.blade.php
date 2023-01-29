@extends('layouts.app')

@section('pageClass', 'user-page')

@section('content')
    @include('components.navigation')
    @yield('subNavigation')
    <main class="main has-wrapper @yield('mainClass')">
        @yield('main')
    </main>
    <dialogs-wrapper></dialogs-wrapper>
@endsection
