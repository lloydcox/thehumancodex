<!DOCTYPE html>
<html>
<head>
    <title>The Human Codex - My Comments</title>
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
            margin-top:0px;
            font-size:small;
        }
        .footer{
            margin-top:15px;
            font-size:smaller;
        }
    </style>
</head>
<body>


<div class="navigation large-shadow">
    The Human Codex | Comments you have made
</div>

<main>
    @foreach($comments as $index=>$commentList)
        <section>
            <h6>{{ $index }}</h6>
            <hr>
            @foreach($commentList as $comment)
                <div class="card">
                    <h5>{{$comment->content}}</h5>
                    <hr>
                    <a href="{{$baseURL}}/?view_notification_post={{$comment->post->id}}">{{$comment->post->title}}</a>
                    @if($comment->post->user_id !== $comment->user_id)
                        <p style="font-size:xx-small; margin-top:3px">Post by {{$comment->post->user->first_name}}</p>
                    @else
                        <p style="font-size:xx-small; margin-top:3px">Post by Me</p>
                    @endif
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

