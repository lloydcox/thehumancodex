@extends('layouts.user')

@section('main')
    <section class="main-container">
        <search-results-users :results="{{ $results['users'] }}"></search-results-users>
        {{--<search-results-posts :results="{{ $results['posts'] }}"></search-results-posts>--}}
    </section>
@endsection