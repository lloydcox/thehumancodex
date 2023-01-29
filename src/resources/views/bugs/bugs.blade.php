@extends('layouts.public')

<meta name="csrf-token" content="{{ csrf_token() }}">
<script>window.Laravel = {csrfToken: '{{csrf_token()}}' }</script>

@section('title', 'Report a Bug')
@section('main')

    <app-bugs></app-bugs>

@endsection
