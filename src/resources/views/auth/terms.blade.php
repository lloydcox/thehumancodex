@extends('layouts.public')

@section('title', 'Just a few more...')

@section('subtitle', 'Terms of service')

@section('containerClass', 'is-white')

@section('main')
    <div class="box-container is-narrow">
        <p class="has-text-left has-text-grey">
            I agree that I will treat anyone and everyone on The Human Codex - regardless of their race, religion, national origin, ethnicity, disability, sex, gender identity, secual orientation or age - with respect and without judgement or bias.
        </p>
        <p>
            <form method="POST">
                {!! csrf_field() !!}
                <button type="submit" class="button is-rounded is-primary is-flat is-wide">
                    I accept
                </button>
            </form>
        </p>
        <p>
            <a href="/register/terms/decline" class="button is-rounded is-flat is-wide has-text-primary">
                I decline
            </a>
        </p>
    </div>
@endsection