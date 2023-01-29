@extends('layouts.public')

@section('title', 'Forgot password?')

@section('subtitle', 'No worries, you will get new one in split of a second!')

@section('main')
    <forgot-password url="{{ route('forgotPassword') }}">
    </forgot-password>
@endsection