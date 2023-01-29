@extends('layouts.app')

@section('content')
    <div class="public-container @yield('containerClass')">
        @include('components.navigation')
{{--        @if(Auth::check())--}}
{{--            @include('components.navigation')--}}
{{--        @else--}}
{{--            <header style="background-color: white!important;">--}}
{{--            <nav class="navbar navbar-light">--}}
{{--                <div data-v-69e17f4e="" class="navbar-brand"><a data-v-69e17f4e="" href="/" class="navbar-item">--}}
{{--                        thehumancodex--}}
{{--                        <sub data-v-69e17f4e="" class="tag is-danger beta-tag">Beta</sub></a>--}}
{{--                </div>--}}
{{--            </nav>--}}
{{--            </header>--}}
{{--        @endif--}}
        <main class="main has-text-centered mt-5 pt-5">
            <h1 class="page-title">@yield('title')</h1>
            <h3 class="page-subtitle">@yield('subtitle')</h3>
            @yield('main')
        </main>
    </div>
    <div class="public-footer">
        © The Human Codex 2018
    </div>
@endsection
