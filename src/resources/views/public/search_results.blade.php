@extends('layouts.app')

@section('title', 'FAQ')

@section('content')
    @if($results !== null)
    <header>
        <!--Nav-Bar -->
        <nav class="navbar navbar-light bg-light font-change">
            <a class="my-1 container-fluid header-back-logo" href="/">thehumancodex</a>
        </nav>
        <!--FAQ Text-->

        <a type="button" class="button is-rounded is-circle m-4" href="javascript:history.back()">
            <i class="fa fa-arrow-left"></i>
        </a>
        <h1 class="text-center font-change display-5">FAQ</h1>
        <br>
    </header>

    <!--Search Box After Search-->
    <div class="container" style="margin-top: 27px">
        <div class="col-10 col-lg-8 col-md-9 col-sm-9 col-xl-6" style="margin: auto;">
            <form action="{{route('faq_search')}}" method="post" class="col-12">
                {{ csrf_field() }}
                <div>
                    <span class="fa fa-search search-icon"></span>
                    <input type="text" class="text-change-after-search search-box-width" placeholder="Search" name="keyword" required value="{{$keyword}}">
                    <button type="submit" class="btn btn-primary fa fa-search clear-button search-button" style="color: white;background-color: #2abace"></button>
                </div>
            </form>
        </div>
    </div>
    <br>
    <br>

    <!--After Search question-->
    <div class="container">
        @foreach($results as $result)
            <div class="container" style="margin-top: 12px">
                <div class="col-xl-10">
                    <div class="text-left" style="overflow: hidden">
                    <a class="question-style display-6" href="{{url('/showAnswerPage/'.$result['slug'])}}">{{ $result['question'] }}</a>
                    <p>
                        {{ $result['answer'] }}
                    </p>
                </div>
                </div>
            </div>
            <br>
            <br>
        @endforeach
    </div>
    @else
        <header>
            <!--Nav-Bar -->
            <nav class="navbar navbar-light bg-light font-change">
                <a class="my-1 container-fluid header-back-logo" href="/">thehumancodex</a>
            </nav>
            <!--FAQ Text-->

            <a type="button" class="button is-rounded is-circle m-4" href="javascript:history.back()">
                <i class="fa fa-arrow-left"></i>
            </a>
            <h1 class="text-center font-change display-5">FAQ</h1>
            <br>
        </header>

        <!--Search box-->
        <div class="container" style="margin-top: 27px">
            <div class="col-10 col-lg-8 col-md-9 col-sm-9 col-xl-6" style="margin: auto;">
                <form action="{{route('faq_search')}}" method="post" class="col-12">
                    {{ csrf_field() }}
                    <div>
                        <span class="fa fa-search search-icon"></span>
                        <input type="text" class="text-change-after-search search-box-width" placeholder="Search" name="keyword" required value="{{$keyword}}">
                        <button type="submit" class="btn btn-primary fa fa-search clear-button search-button" style="color: white;background-color: #2abace"></button>
                    </div>
                </form>
            </div>
        </div>
        <br>
        <br>

        <!--empty screen -->
        <div class="container">
            <div class="col-10 col-lg-10 col-md-10 col-xl-8" style="margin: auto">
                <div class="alert alert-danger text-center">
                    <strong>Sorry !</strong> {{ $message }}
                </div>
            </div>
        </div>
        <div class="container">
            <div class="col-10 col-xl-4 text-center" style="margin: auto;">
                <h3>For more Information</h3>
            </div>
        </div>

        <div class="container" style="margin-top: 27px">
            <div class="col-5 col-lg-4 col-sm-5 col-xl-3 text-center" style="margin: auto;">
                <button class="btn-more-info btn btn-primary" style="width: -webkit-fill-available" onclick="location.href='/contact'">Contact Us</button>
            </div>
        </div>

    @endif
@endsection
@section('script')

@endsection