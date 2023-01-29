@extends('layouts.user')

@section('main')
    <section class="main-container">
        <div class="card has-padding-2">
            <search-form action="{{ route('search') }}"></search-form>
        </div>
    </section>
@endsection