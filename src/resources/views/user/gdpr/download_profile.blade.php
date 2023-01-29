<!DOCTYPE html>
<html>
<head>
    <title>The Human Codex - My Profile</title>
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
    The Human Codex | Profile data on THC account
</div>

<main>
    <section>
        <div class="card">
            <br><br>
            @if(count($profile->data) !== 0)
                @foreach($profile->data as $data)
                    @if($data->code !== null)
                        @if($data->code === 'avatar')
                            @if($data->value !== null)
                                <img style="display:block; height:100px;" src="{{$public_path}}{{$data->value}}">
                            @endif
                        @endif
                        @if($data->code === 'description')
                            @if($data->value !== null)
                                <h5>My Message to Humanity</h5>
                                <p>{{$data->value}}</p>
                            @endif
                        @endif
                    @endif
                @endforeach
            @endif
            <hr>
            @if($profile->first_name !== null)
                <h5>First Name</h5>
                <p>{{$profile->first_name}}</p>
            @endif
            @if($profile->last_name !== null)
                <h5>Last Name</h5>
                <p>{{$profile->last_name}}</p>
            @endif
            @if($profile->gender !== null)
                <h5>Gender</h5>
                <p>{{$profile->gender}}</p>
            @endif
            @if($profile->age !== null)
                <h5>Date of Birth</h5>
                <p>{{$profile->age}}</p>
            @endif
            @if($profile->location !== null)
                <h5>Location</h5>
                <p>{{$profile->location}}</p>
            @endif
            <hr>
            @if(count($profile->categoryInputs) !== 0)
                @foreach($profile->categoryInputs as $categoryInput)
                    @if($categoryInput->post_category_id === 1)
                        @if($categoryInput->input !== null)
                            <h5>Thoughts/Ideas</h5>
                            <p>{{$categoryInput->input}}</p>
                        @endif
                    @endif
                    @if($categoryInput->post_category_id === 2)
                        @if($categoryInput->input !== null)
                            <h5>Work: Your Career and Finance</h5>
                            <p>{{$categoryInput->input}}</p>
                        @endif
                    @endif
                    @if($categoryInput->post_category_id === 3)
                        @if($categoryInput->input !== null)
                            <h5>Story</h5>
                            <p>{{$categoryInput->input}}</p>
                        @endif
                    @endif
                    @if($categoryInput->post_category_id === 4)
                        @if($categoryInput->input !== null)
                            <h5>Learning: Your Personal Development</h5>
                            <p>{{$categoryInput->input}}</p>
                        @endif
                    @endif
                    @if($categoryInput->post_category_id === 5)
                        @if($categoryInput->input !== null)
                            <h5>Social: Your Relationship with others</h5>
                            <p>{{$categoryInput->input}}</p>
                        @endif
                    @endif
                    @if($categoryInput->post_category_id === 6)
                        @if($categoryInput->input !== null)
                            <h5>Advice</h5>
                            <p>{{$categoryInput->input}}</p>
                        @endif
                    @endif
                @endforeach
            @endif
        </div>
    </section>
</main>

<div class="navigation large-shadow footer">
    Copyright @ The Human Codex
</div>

</body>
</html>

