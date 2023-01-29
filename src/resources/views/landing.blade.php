@extends('layouts.app')

@section('content')
    <landing-page>
        <!-- Define login content -->
        <template slot="login">
            <section class="section is-paddingless">
                <header class="page-header">
                    <h2 class="page-title">Not a member yet?</h2>
                    <h3 class="page-subtitle">Join the community in just couple clicks</h3>
                </header>
                <p>
                    <a class="button is-rounded" href="{{ url('login/google') }}" disabled>
                        <img src="/images/google-logo.svg" alt="g" class="icon">
                        Continue with Google
                    </a>
                </p>
                <p>
                    <button class="button is-rounded" disabled>
                        <img src="/images/facebook-logo.svg" alt="fb" class="icon">
                        Continue with Facebook
                    </button>
                </p>
                <p>
                    <a href="{{ url('register') }}" class="button is-rounded">Continue with email</a>
                </p>
            </section>
            <section class="section">
                <header class="page-header">
                    <h2 class="page-title">Already with us?</h2>
                    <h3 class="page-subtitle">Log in, connect and shere moments</h3>
                </header>
                <p>
                    <a href="/login" class="button is-rounded is-primary is-flat">Log in</a>
                </p>
            </section>
            <p class="is-size-7">
                By continuing you agree to The Human Codex <a href="{{ url('terms') }}">Terms of Service</a> and <a href="{{ url('policy') }}">Privacy
                    Policy</a>.
            </p>
        </template>
        <!-- Define footer -->
        <template slot="footer">
            <div class="level">
                <div class="level-left">
                    <div class="level-item">
                        <a href="{{ url('about') }}">About</a>
                    </div>
                    <div class="level-item">
                        <a href="{{ url('faq') }}">FAQ</a>
                    </div>
                    <div class="level-item">
                        <a href="{{ url('rules') }}">Legal</a>
                    </div>
                    <div class="level-item">
                        <a href="{{ url('report_bug') }}">Report a bug</a>
                    </div>
                    <div class="level-item">
                        <a href="{{ url('contact') }}">Contact</a>
                    </div>
                </div>
                <div class="level-right">
                    <div class="level-item text-grey">
                        @ The Human Codex 2018
                    </div>
                </div>
            </div>
        </template>
        <!-- Define slides -->
        <template slot="slide1">
            <header class="page-header">
                <h2 class="page-title is-size-1">The Human Codex</h2>
                <h3 class="page-subtitle is-size-4">Human history of the people, by the people, for the people</h3>
            </header>
            <p class="is-size-5">
                The Human Codex aims to be the first ever human history record of the people, created by the people, 
                for the people. Its purpose is simple, it is a place where everyone can come 
                and leave their own personal mark in history..
            </p>
        </template>
        <template slot="slide2">
            <header class="page-header">
                <h2 class="page-title is-size-1">The Human Codex</h2>
                <h3 class="page-subtitle is-size-4">Guiding our future, by sharing your past</h3>
            </header>
            <p class="is-size-5">
                We hope to help the future generations have a better understanding of 
                life and all that it involves. Our users will be passing on life lessons, experiences and 
                knowledge by telling their life story. The future generations will have a pool of knowledge unlike any before. 
            </p>
        </template>
        <template slot="slide3">
            <header class="page-header">
                <h2 class="page-title is-size-1">The Human Codex</h2>
                <h3 class="page-subtitle is-size-4">Safeguarding humanities Heritage, Culture and Personal history</h3>
            </header>
            <p class="is-size-5">
                We are extending the tradition of storing relevant items by communities (or individuals) 
                in containers that will carry them in to the future and digitalising this process. Rather than waiting deep inside the 
                ground to unfold the image of the past when opened, we will store the Capsules (aka Codex) in a digital format. The practice is 
                connecting two points in time and preserving the zeitgeist of the era so that the descendants of those who participate will 
                have a truly accurate historical record of their ancestors.
            </p>
        </template>
    </landing-page>
@endsection
