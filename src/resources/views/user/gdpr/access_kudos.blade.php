@extends('layouts.user')
@section('main')
    <settings-my-kudos :kudos="{{ $kudos }}" :post-categories="{{$categories}}"></settings-my-kudos>
@endsection
