@extends('layouts.user')
@section('main')
    <settings-my-connections :connections="{{$connections}}"></settings-my-connections>
@endsection
