@extends('layouts.public')

@section('title', 'Already with us?')

@section('subtitle', 'Log in, connect and share the moments')

@section('main')
    <login-box></login-box>
    @include('auth.components._terms')
@endsection