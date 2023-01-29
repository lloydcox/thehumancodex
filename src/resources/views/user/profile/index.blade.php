@extends('layouts.user')

@section('main')
    <section class="main-container" id="user_profile">
        @include('user.profile.components._header', compact('user'))

        {{-- Don't remove following commented code, it might be valuable in future --}}
{{--        @if(request()->user()->id == $user->id)--}}
{{--            <timeline-form :user="{{ $user }}" post-url="{{ url('/post') }}"></timeline-form>--}}
{{--            <new-requests user-id="{{ $user->id }}"></new-requests>--}}
{{--        @endif--}}
{{--        <connections user-id="{{ $user->id }}" :allow-adding-and-removing="{{ request()->user()->id === $user->id ? 'true' : 'false' }}"></connections>--}}
{{--        <div class="spacer is-large">--}}
{{--            @if(request()->user()->id === $user->id)--}}
{{--                @include('user.profile.components._switcher', [--}}
{{--                    'profileUrl' =>  url('profile'),--}}
{{--                    'mapUrl' => url('profile/map')--}}
{{--                ])--}}
{{--            @else--}}
{{--                @include('user.profile.components._switcher', [--}}
{{--                    'profileUrl' =>  url("codex/{$user->username}"),--}}
{{--                    'mapUrl' => url("codex/{$user->username}/map")--}}
{{--                ])--}}
{{--            @endif--}}
{{--        </div>--}}
{{--        --}}{{--<a href="../"><button ></button></a>--}}
{{--         <timeline user-id="{{ $user->id }}"></timeline>--}}

        <div class="section mb-0">
            @if(Request::url() === URL::to('/').'/profile')
            <div class="profile-nav">
                <a href="{{route('personal-map')}}">
                    <button class="btn btn-primary mb-3"> geographical journey</button>
                </a>
                <a href="{{route('horizontal-timeline')}}">
                    <button class="btn btn-primary mb-3">chronological journey</button>
                </a>
            </div>
            @endif


            <div class="section-title text-muted">About Me</div>

            @foreach($categoryInputs as $categoryInput)
                <div class="row mt-4 mb-2">
                    <div class="col-md-8 col-sm-12">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="color-box" style="{{'background-color:'. $categoryInput->color_code.';'}}"></div>
                            </div>
                            <div class="col-md-10 mt-2 mt-md-0 has-text-left pl-3 pl-md-0">
                                <div class="h6 profile-about-title"> {{$categoryInput->title}} <sup><span class="badge badge-pill badge-info">{{$categoryInput->momentCount}}</span></sup></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 text-left text-md-right">
                       @if($categoryInput->momentCount > 0)
                            <a  class="moment-count" href="{{'/?view_category_posts='.$categoryInput->id.'&user_id='.$user->id}}">View All <i class="fas fa-external-link-alt"></i></a>
                        @endif
                    </div>
                </div>
                <hr>
                @if(isset($categoryInput->input['input']))
                    <div class="desc mb-3">{{$categoryInput->input['input']}}</div>
                @else
                    <div class="desc mb-3"></div>
                @endif
                <div class="row">
                @foreach(array_slice($categoryInput->posts, 0, 4) as $categoryPost)

                        <div class="col-md-6 col-sm-12">
                            <div class="card moment-card">
                                <div class="card-header">
                                    <h5 class="moment-title">{{$categoryPost->title}}</h5>
                                </div>
                                <div class="card-body">
                                    <p class="moment-description">{{$categoryPost->content}}</p>
                                    <a class="moment-link" href="{{'/?view_notification_post='.$categoryPost->id}}">View Moment</a>
                                </div>
                            </div>
                        </div>

                @endforeach
                </div>
            @endforeach

        </div>

       <div class="section mt-0">
           <div class="section-title text-muted">Moments By Category</div>
           <div class="row">
               <div class="col-md-12">
                   <div class="card">
{{--                       <div class="card-header">--}}
{{--                           <div class="row p-4">--}}
{{--                               @foreach($categoryInputs as $catIndex => $cat)--}}
{{--                                   <div class="col-md-6">--}}
{{--                                       <div class="row mt-1">--}}
{{--                                           <div class="col color-box" style="{{'background-color:'. $categoryColors[$catIndex].';'}}"></div>--}}
{{--                                           <div class="col-sm-10 my-auto">--}}
{{--                                               <p class="small-text">{{$cat->title}}</p>--}}
{{--                                           </div>--}}
{{--                                       </div>--}}
{{--                                   </div>--}}
{{--                               @endforeach--}}
{{--                           </div>--}}
{{--                       </div>--}}
                       <div class="card-body" id="chart_wrapper">
                           {!! $chart->container() !!}
                       </div>
                   </div>
               </div>
           </div>
       </div>
    </section>
@endsection
