@extends('layouts.public')

@section('title', 'Check your email')

@section('subtitle', 'It should already be there')

@section('containerClass', 'is-white')

@section('main')
    <div class="box-container is-narrow">
        <p class="has-text-left has-text-grey" style="min-height: 150px">
            Hey, we’ve reseted your password. Try to remember next one, will you?
        </p>
        <p>
            <a href="/login" class="button is-rounded is-primary is-flat is-wide">
                Log in
            </a>
        </p>
    </div>
@endsection