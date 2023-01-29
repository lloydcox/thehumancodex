@extends('layouts.user')
@section('main')
    <settings-my-moments :posts="{{ $moments }}" :post-categories="{{$categories}}"></settings-my-moments>
@endsection
