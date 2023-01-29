@extends('layouts.public')

@section('title', 'Just a few more...')

@section('subtitle', 'Cookies')

@section('containerClass', 'is-white')

@section('main')
    <div class="box-container is-narrow">
        <p class="has-text-left has-text-grey">
            This website uses 'cookies' to give you the best, most relevant experience.
            Using this website means you are okay with this. You can learn more about our cookie policy by clicking <a href="{{route('cookies_details')}}" target="_blank" class="alert-link">this link</a>.
        </p>
        <p>
        <form method="POST">
            {!! csrf_field() !!}
            <button type="submit" class="button is-rounded is-primary is-flat is-wide">
                I agree
            </button>
        </form>
        </p>
    </div>
@endsection