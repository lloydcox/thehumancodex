@extends('layouts.user')

@section('main')
    <div class="main-container">
        <div class="card">
            <div class="card-content">
                <h2 class="page-title">Connect with your contacts already in The Human Codex</h2>
                <p>We’ll browse your contacts in address book to see if any of them are already with us.</p>
            </div>
        </div>
        <invitations :users="{{ $results }}" on-send-redirect="{{ url('setup/moment') }}"></invitations>
    </div>
@endsection