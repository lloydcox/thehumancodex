@extends('layouts.public')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>window.Laravel = {csrfToken: '{{csrf_token()}}' }</script>
@section('title', 'Contact')

@section('main')
    <app-contact></app-contact>

@endsection
