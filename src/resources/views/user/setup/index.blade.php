@extends('layouts.user')

@section('main')
    <div class="section">
        <div class="main-container has-text-centered">
            <header class="page-header">
                <h2 class="page-title">Welcome to The Human Codex!</h2>
                <h3 class="page-subtitle">Seems like you haven’t yet connected with anyone. You can do it right away
                    so this blank timeline wont be so blank!</h3>
            </header>
            <div class="spacer">
                <a href="{{ url('setup/contacts') }}" class="button is-primary is-rounded is-flat">Connect with your family and friends</a>
            </div>
            <div class="spacer">
                <a href="{{ url('setup/moment')  }}" class="button is-transparent has-text-primary is-rounded is-flat">I’ll do that later</a>
            </div>
        </div>
    </div>
@endsection