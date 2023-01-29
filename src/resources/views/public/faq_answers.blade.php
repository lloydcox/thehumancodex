@extends('layouts.public')

@section('title', 'FAQ')

@section('main')
    <div class="container">
        <div class="container">
            <div class="jumbotron jumbotron-fluid shadow text-center" style="background-color: white!important;border-radius: 25px">
                <h1 class="display-5 p-2" >{{$section}}</h1>
                <br>
                @if($type==1)
                    <p class="p-2">{{$answer}}</p>
                @elseif($type==2)
                    <video width="1000" height="800" controls>
                        <source src="{{ URL::asset($answer)}}" type="video/mp4">
                    </video>
                @endif
            </div>
        </div>
    </div>
    <a type="button" class="button is-rounded is-circle m-4" href="javascript:history.back()">
        <i class="fa fa-arrow-left"></i>
    </a>
@endsection
