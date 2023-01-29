@extends('layouts.public')

@section('title', 'Cancel joining?')

@section('subtitle', 'Terms of service')

@section('containerClass', 'is-white')

@section('main')
    <div class="box-container is-narrow">
        <p class="has-text-left has-text-grey">
            Declining this commitment means that you won’t be able to register as a member of The Human Codex.
        </p>
        <p>
            <a href="/register/terms/" class="button is-rounded is-primary is-flat is-wide">
                I’ve changed my mind, go back
            </a>
        </p>
        <p>
            <form method="POST">
                {!! csrf_field() !!}
                <button type="submit" class="button is-rounded is-flat is-wide has-text-primary">
                    Cancel joining
                </button>
            </form>
        </p>
    </div>
@endsection