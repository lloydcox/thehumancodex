@extends('layouts.user')

@section('main')
    <div class="section">
        <div class="main-container has-text-centered">
            <header class="page-header">
                <h2 class="page-title">Let’s create your first Chronicle</h2>
                <h3 class="page-subtitle">While you are waiting to see your friends and families Chronicle's - why not create your own?
                    Simply tap on the fields below to start writing.</h3>
            </header>
            <first-post-form></first-post-form>
            <div class="spacer">
                <a href="{{ url('/')  }}" class="button is-transparent has-text-primary is-rounded is-flat">I’ll do that later</a>
            </div>
        </div>
    </div>
@endsection