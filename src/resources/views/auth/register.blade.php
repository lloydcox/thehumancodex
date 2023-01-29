@extends('layouts.public')

@section('title', 'Not a member yet?')

@section('subtitle', 'Join the community in just couple of clicks')

@section('main')
    <register-box url="{{ route('login') }}">
    </register-box>
    @include('auth.components._terms')
@endsection