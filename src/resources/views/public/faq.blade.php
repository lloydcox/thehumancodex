@extends('layouts.public')

@section('title', 'FAQ')

@section('main')
    <!--Search Box-->
    <div class="container" style="margin-top: 27px">
        <div class="col-10 col-lg-8 col-md-9 col-sm-9 col-xl-6" style="margin: auto;">
            <form action="{{route('faq_search')}}" method="post" class="col-12">
                {{ csrf_field() }}
                <div>
                    <span class="fa fa-search search-icon"></span>
                    <input type="text" class="text-change-after-search search-box-width" placeholder="Search" name="keyword" required>
                    <button type="submit" class="btn btn-primary fa fa-search clear-button search-button" style="color: white;background-color: #2abace"></button>
                </div>
            </form>
        </div>
    </div>
        <br>
        <!--Question Section-->
        <div class="container" id="font-change" >
            <div class="row ">
               {{--cards--}}
               @foreach($topQuestions as $key => $question)
                       <div class="col-sm-4 text-center card-border">
                           <h1 class= "que-color display-6">{{ $question['question']}}</h1>
                           <br>
                           <p class="display-7">
                               {{$question['answer']}}
                           </p>
                       </div>
                   @endforeach
            </div>
        </div>
        <br>
        <br>
    <!--Collapse Section-->
    <div class="container">
        <div class="row" style="margin: 0">

            @foreach(config('constance')['faq'] as $category => $questionSet)

                <aside class="col-sm-6">

                    <!--category-one-->
                    <div class="card" id="card">
                        {{--<header class="card-header">--}}
                            <a class="collapsible-card">
                                <i id="icon-changer" class="icon-action  fas fa-plus panel-title" ></i>&nbsp;
                                <span class="" >{{$category}}</span>
                            </a>
                        {{--</header>--}}
                        <div class="content-card" id="collapse11" style="">
                            <article class="card-body">
                                <ul class="text-left" style="list-style: disc">
                                    @foreach($questionSet as $questionBlock)
                                        <li id="none"><a href="{{url('/showAnswerPage/'.$questionBlock['slug'])}}"  >{{$questionBlock['question']}}</a></li>
                                    @endforeach
                                </ul>
                            </article> <!-- card-body.// -->
                        </div> <!-- collapse .// -->
                    </div> <!-- card.// -->
                </aside>
            @endforeach



        </div> <!-- row.// -->
    </div>
    <!--container end.//-->

        <!--Contact Us Button-->
        <br>
    <div class="container">
        <div class="col-10 col-xl-4 text-center" style="margin: auto;">
            <h3>If doesn't fulfill your problem...</h3>
        </div>
    </div>

    <div class="container">
        <div class="col-5 col-lg-4 col-sm-5 col-xl-3 text-center" style="margin: auto;">
            <button class="btn-more-info btn btn-primary" style="width: 100%" onclick="location.href='/contact'">Contact Us</button>
        </div>
    </div>
    <br>
@endsection
@section('script')
    <script>
        var coll = document.getElementsByClassName("collapsible-card");
        var i;

        for (i = 0; i < coll.length; i++) {
            coll[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var icon=this.children[0];
                var content = this.nextElementSibling;
                if (content.style.maxHeight){
                    content.style.maxHeight = null;
                    icon.classList.add("fa-plus");
                    icon.classList.remove("fa-window-minimize");
                } else {
                    content.style.maxHeight = content.scrollHeight + "px";
                    icon.classList.remove("fa-plus");
                    icon.classList.add("fa-window-minimize");
                }
            });
        }
    </script>
@endsection
