<!DOCTYPE html>
<html>
<head>
    <title>The Human Codex - My Moments</title>
    <style>
        body{
            background-color:#f7f7f9;
            padding:0px;
            font-family: "Ubuntu", sans-serif;
        }
        .large-shadow{
            box-shadow: 0px 3px 6px #00000029;
        }
        .navigation{
            text-align:center;
            background-color:#ffffff;
            padding-top:10px;
            padding-bottom:10px;
            border-radius:5px;
            color:#4a4a4a;
        }
        main{
            padding-left:5%;
            padding-right:5%;
            width:auto;
            padding-top:10px;
            padding-bottom:10px
        }
        h6{
            margin-bottom:5px;
            font-family: "Ubuntu", sans-serif;
            font-weight:600;
        }
        h5{
            font-family: "Ubuntu", sans-serif;
            font-weight:600;
            text-overflow:ellipsis;
            margin-top:5px;
            margin-bottom:5px;
        }
        .card{
            margin-top:10px;
            width:98%;
            box-shadow: 0px 2px 4px #00000029;
            border: 1px solid black;
            border-radius: 2px;
            background-color: #FFFFFF;
            padding-left:1%;
            padding-right:1%;
            padding-top:2px;
            padding-bottom:2px;
        }
        p{
            margin-top:0px;
            font-size:small;
        }
        a{
            color:blue;
        }
        a:hover {
            color: darkblue;
        }
        .footer{
            margin-top:15px;
            font-size:smaller;
        }
    </style>
</head>
<body>


<div class="navigation large-shadow">
    The Human Codex | Moments you have posted
</div>

<main>
    @foreach($posts as $index=>$postList)
        <section>
            <h6>{{ $index }}</h6>
                <hr>
                @foreach($postList as $post)
                    <div class="card">
                        <h5>{{$post->title}}</h5>
                        <hr>
                        <p>{{$post->location}} | {{$post->created_at}} | {{$post->postCategory->title}} | Kudos: {{count($post->kudos)}} | Comments: {{count($post->comments)}}</p>
                        <p>{{$post->content}}</p>
                        <p><a>{{$post->youtube_url}}</a></p>
                    </div>
                @endforeach
        </section>
    @endforeach
</main>

<div class="navigation large-shadow footer">
    Copyright @ The Human Codex
</div>

</body>
</html>

