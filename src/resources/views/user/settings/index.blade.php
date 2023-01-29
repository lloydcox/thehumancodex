@extends('layouts.user')

@section('main')
    <section class="main-container">
        <section class="spacer is-large">
            <h5 class="section-name">Account</h5>
            <thc-button-component url="{{ url('settings/profile') }}">
                <template slot="image">
                    <img src="{{ request()->user()->avatar }}" alt="" class="is-rounded">
                </template>
                <template slot="name">
                    Edit profile
                </template>
                <template slot="description">
                    Update your cover image, photo, name, bio.
                </template>
            </thc-button-component>
        </section>
        <section class="spacer is-large">
            <h5 class="section-name">Security</h5>
            <thc-button-component url="{{ url('settings/account') }}">
                <template slot="icon">
                    <i class="fa fa-user"></i>
                </template>
                <template slot="name">
                    Login
                </template>
                <template slot="description">
                    Review your logins and set up your password.
                </template>
            </thc-button-component>
        </section>
        <section class="spacer is-large">
            <h5 class="section-name">Notifications</h5>
            <thc-button-component url="{{ url('settings/notifications') }}" disabled>
                <template slot="icon">
                    <i class="far fa-bell"></i>
                </template>
                <template slot="name">
                    Notifications and activity
                </template>
                <template slot="description">
                    Control how we deliver new content to you.
                </template>
            </thc-button-component>
        </section>
        <section class="spacer is-large">
            <h5 class="section-name">Your The Human Codex information</h5>
            <thc-button-component url="{{ url('settings/access-data') }}">
                <template slot="icon">
                    <i class="fas fa-database"></i>
                </template>
                <template slot="name">
                    Access your data
                </template>
                <template slot="description">
                    Access data you shared with us.
                </template>
            </thc-button-component>
            <thc-button-component url="{{ url('settings/data/download') }}">
                <template slot="icon">
                    <i class="fas fa-file-download"></i>
                </template>
                <template slot="name">
                    Download your data
                </template>
                <template slot="description">
                    Download a copy of your information.
                </template>
            </thc-button-component>
            <thc-button-component url="{{ url('settings/data/delete') }}">
                <template slot="icon">
                    <i class="fas fa-trash has-text-danger"></i>
                </template>
                <template slot="name">
                    Delete your account
                </template>
                <template slot="description">
                    Delete your account and information permanently.
                </template>
            </thc-button-component>
        </section>
        <section class="spacer is-large">
            <h5 class="section-name">Legal information</h5>
            <thc-button-component url="{{ url('terms') }}">
                <i class="fas fa-file-contract mr-2"></i>
                Terms of service
            </thc-button-component>
            <thc-button-component url="{{ url('policy') }}">
                <i class="fas fa-user-secret mr-2"></i>
                Privacy and data policy
            </thc-button-component>
            <thc-button-component url="{{ url('rules') }}">
                <i class="fab fa-leanpub mr-2"></i>
                Rules & Guidelines
            </thc-button-component>
            <thc-button-component url="{{ url('cookies') }}">
                <i class="fas fa-cookie mr-2"></i>
                Cookies
            </thc-button-component>
            <thc-button-component url="{{ url('about') }}">
                <i class="fas fa-home mr-2"></i>
                About
            </thc-button-component>
            <thc-button-component url="{{ url('contact') }}">
                <i class="fas fa-phone mr-2"></i>
                Contact
            </thc-button-component>
            <thc-button-component url="{{ url('faq') }}">
                <i class="fas fa-question-circle mr-2"></i>
                FAQ
            </thc-button-component>
            <thc-button-component url="{{ url('logout') }}">
                <span class="has-text-danger">Log out</span>
            </thc-button-component>
        </section>
    </section>
@endsection
