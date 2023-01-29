@extends('layouts.user')
@section('main')
    <settings-my-comments :comments="{{ $comments }}" :post-categories="{{$categories}}"></settings-my-comments>
@endsection
