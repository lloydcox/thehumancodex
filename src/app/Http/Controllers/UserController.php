<?php

namespace App\Http\Controllers;

use App\Charts\MomentsByCategory;
use App\Comments;
use App\Connection;
use App\PostCategory;
use App\Social;
use App\UserCategoryInput;
use App\UserData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Post;
use App\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->loadNotifications();
        $user_id = Auth::user()->id;
        $data = $this->data;

        //Collect user data
        $user = User::findOrFail($user_id);
        $data['user'] = $user;
        //Format age date
        $data['user']->age = date('F d, Y', strtotime($data['user']->age));

        $user_data = UserData::where('user_id','=',$user_id)->get();
        foreach ($user_data->toArray() as $entry) {
            $data['user_data'][$entry['code']] = $entry['value'];
        }

        //Collect post data
        $connections_ids1 = Connection::where('user_id', $user_id)->pluck('connected_user_id')->toArray();
        $connections_ids2 = Connection::where('connected_user_id', $user_id)->pluck('user_id')->toArray();
        $connections_ids = array_merge($connections_ids1, $connections_ids2);
        $posts = Post::whereIn('user_id',$connections_ids)->get();
        $data['posts'] = $posts;

        return view('profile', $data);
    }

    public function settings()
    {
        $this->loadNotifications();
        $user_id = Auth::user()->id;
        $data = $this->data;
        //Collect user data
        $user = User::findOrFail($user_id);

        $auth_user = UserData::where('user_id', '=',$user_id)->where('code', '=', 'image')->first();
        if($auth_user) {
            $data['user_image'] = $auth_user->value;
        }


        $data['user'] = $user;
        //Format age date
        $data['user']->age = date('F d, Y', strtotime($data['user']->age));


        $user_socials = Social::where('user_id','=',$user_id)->first();
        if($user_socials) {
            $data['user_socials'] = $user_socials->toArray();
        }

        $user_data = UserData::where('user_id','=',$user_id)->get();
        foreach ($user_data->toArray() as $entry) {
            if($entry['code']=='image'){
                $data['user_data']['avatar'] = $entry['value'];
            }else {
                $data['user_data'][$entry['code']] = $entry['value'];
            }
        }

        //Collect post data
        $connections_ids1 = Connection::where('user_id', $user_id)->pluck('connected_user_id')->toArray();
        $connections_ids2 = Connection::where('connected_user_id', $user_id)->pluck('user_id')->toArray();
        $connections_ids = array_merge($connections_ids1, $connections_ids2);

        $connections = User::whereIn('id', $connections_ids)->get();

        $data['connections'] = $connections;

        return view('settings', $data);
    }

    public function save(Request $request)
    {
        $base_fields = array(
            'first-name' => 'first_name',
            'last-name' => 'last_name',
            'location' => 'location',
            'gender' => 'gender',
            'age' => 'age',
            'email' => 'email',
            'password-new' => 'password',
            'username' => 'username'

        );
        $registered_fields = array(
            'profile-picture' => 'image',
            'profile-picture-update' => 'image',
            'location-origin' => 'place_of_birth',
            'user_message' => 'user_bio'
        );



        $posts = $request->all();

        $base = array();

        foreach ($posts as $key => $post)
        {

            if(key_exists($key, $base_fields) && !empty($post)){
                if($key=='password-new'){
                    $hasher = app('hash');
                    if($hasher->check($posts['password'], Auth::user()->getAuthPassword())) {
                        $post = bcrypt($post);
                    }else{
                        return redirect("/settings/")->with('status', 'Wrong password!');
                    }
                }
                $base[$base_fields[$key]] = $post;


            }elseif (key_exists($key,$registered_fields) && !empty($post)){
                $data = UserData::where('user_id', '=', Auth::id())->where('code', '=', $registered_fields[$key])->first();
                if($data){
                    if($key == 'profile-picture-update') {
                        $photoname = $request->{'profile-picture-update'}->getClientOriginalName();
                        $photoname = explode('.',$photoname);
                        $extension = $photoname[count($photoname)-1];
                        $photoname = Auth::user()->username . '.' . $extension;
                        $request->file('profile-picture-update')->move(public_path('storage' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'user'), $photoname);
                        $post = $photoname;
                    }
                    $data->value = $post;
                    $data->saveOrFail();
                }else{
                    if($key == 'profile-picture-update') {
                        $photoname = $request->{'profile-picture-update'}->getClientOriginalName();
                        $photoname = explode('.',$photoname);
                        $extension = $photoname[count($photoname)-1];
                        $photoname = Auth::user()->username . '.' . $extension;
                        $request->file('profile-picture-update')->move(public_path('storage' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'user'), $photoname);
                        $post = $photoname;
                    }
                    UserData::create(array(
                        'user_id' => Auth::id(),
                        'code' => $registered_fields[$key],
                        'value' => $post
                    ));
                }
            }
        }

        $user = User::find(Auth::id());
        $user->fill($base);
        $user->saveOrFail();
        return redirect("/settings/")->with('status', 'Profile updated!');
    }

    public function register()
    {
        echo 'Register action';
    }

    public function user($username)
    {
        //Collect user data
        $user = User::where('username','=',$username)->first();
        $user_id = $user->id;

        $this->getBasicData();
        $data = $this->data;

        $data['user'] = $user;
        //Format age date
        //$data['user']->age = date('F d, Y', strtotime($data['user']->age));
        $user_data = UserData::where('user_id','=',$user_id)->get();
        $user_socials = Social::where('user_id','=',$user_id)->first();
        $data['connection'] = Connection::connectionWith($user);
        if($user_socials) {
            $data['user_socials'] = $user_socials->toArray();
        }
        foreach ($user_data->toArray() as $entry) {
            if($entry['code']=='image'){
                $data['user_data']['avatar'] = $entry['value'];
            }else {
                $data['user_data'][$entry['code']] = $entry['value'];
            }
        }

        //Collect post data
        $posts = Post::where('user_id','=',$user_id)->get();
        foreach ($posts as $post){
            $comments = Comments::where('post_id', '=', $post->id)->get();
            foreach ($comments as $key => $comment)
            {
                $user = User::findOrFail($comment->user_id);
                $comments[$key]['username'] = $user->first_name . ' ' . $user->last_name;

            }
            $data['comments'][$post->id] = $comments;
        }
        $data['posts'] = $posts;

        $allCategories = PostCategory::all();

        $categoryData = [];
        $chartLabels = [];
        $chartValues = [];
        $borderColors = [];
        $fillColors = [];
        $chartImage = $user->avatar;

        foreach ($allCategories as $category){
            $tempArray = [];
            $count = 0;
            foreach ($posts as $post){
                if($post->post_category_id == $category->id){
                    array_push($tempArray, $post);
                    $count++;
                }
            }
            $category->posts = $tempArray;
            $category->momentCount = $count;
            $category->input = UserCategoryInput::where('user_id', Auth::id())
                ->where('post_category_id', $category->id)->select('input')->first();
            array_push($chartLabels, $category->title);
            array_push($chartValues, $count);
            array_push($categoryData, $category);
            array_push($borderColors, $category->color_code);
            array_push($fillColors, $category->color_code);
        }

        $momentsByCategory = new MomentsByCategory();

        $momentsByCategory->minimalist(true);
        $momentsByCategory->labels($chartLabels);
        $momentsByCategory->dataset('Moments By Category', 'doughnut',$chartValues)
            ->color($borderColors)
            ->backgroundcolor($fillColors);

        $data['chart'] = $momentsByCategory;
        $data['categoryInputs'] = $categoryData;
        $data['categoryColors'] = $fillColors;
        $data['chartImage'] = $chartImage;

        return view('user.profile.index', $data);
    }

    public function connect($username, Request $request)
    {
        $user_id = Auth::user()->id;
        $user = User::where('username', '=', $username)->first();
        $connect_id = $user->id;
        $connections = Connection::where('user_id',$user_id)
            ->where('connected_user_id', $connect_id)
            ->count();
        if($connections > 0) {
            return \redirect()->back()->with('error', 'You are already connected with this user');
        }

        $mytime = Carbon::now();
        $mytime->toDateTimeString();

        $result = Connection::create([
            'user_id' => $user_id,
            'connected_user_id' => $connect_id,
            'accepted' => 0,
            'dom' => $mytime,
            'type' => 'public'
        ]);

        $this->createNotification('User ' . Auth::user()->first_name . ' wants to connect with you', 'connection', $result->id);

        return \redirect()->back();

    }

    public function acceptConnection($connectionId)
    {
        $user_id = Auth::user()->id;
        $connections = Connection::where('id',$connectionId)
            ->where('connected_user_id', $user_id)
            ->where('accepted', 0)
            ->first();
        if(!$connections) {
            return \redirect()->back()->with('error', 'We cannot find this request');
        }

        try {
            $connections->accepted = 1;
            $connections->saveOrFail();
        }catch (\Illuminate\Database\QueryException $e){
            return \redirect()->back()->with('error', 'There was issue while trying to accept this request! Please try again.');
        }

        $this->createNotification('User ' . Auth::user()->first_name . ' accepted your connection request', 'connection_accepted', $user_id);

        return \redirect()->back()->with('success', 'Request accepted');;

    }

    public function declineConnection($connectionId)
    {
        $user_id = Auth::user()->id;
        $connections = Connection::where('id',$connectionId)
            ->where('connected_user_id', $user_id)
            ->where('accepted', 0)
            ->first();
        if(!$connections) {
            return \redirect()->back()->with('error', "We cannot find this request");
        }

        try {
            $connections->delete();
        }catch (\Illuminate\Database\QueryException $e){
            return \redirect()->back()->with('error', 'There was issue while trying to delete this request! Please try again.');
        }

        return \redirect()->back()->with('success', 'Request deleted');

    }

    public function disconnect(Request $request)
    {
        $deleted = false;
        $user_id = Auth::user()->id;
        $username = $request->get('users');
        $connected_user_id = User::where('username', '=', $username)->first()->id;
        $connection = Connection::where('user_id', '=', $user_id)
            ->where('connected_user_id', '=', $connected_user_id)
            ->where('accepted', 1)
            ->first();
        if($connection){
            $connection->delete();
            $deleted = true;
        }
        $connection = Connection::where('connected_user_id', '=', $user_id)
            ->where('user_id', '=', $connected_user_id)->first();
        if($connection){
            $connection->delete();
            $deleted = true;
        }
        if($deleted) {
            return \redirect()->back()->with('success', "Disconnected from user $username");
        }

        return \redirect()->back()->with('error', "Unable to disconnect  $username");

    }

    public function mute()
    {
        //disable notifications from user
    }

    public function unmute()
    {
        //enable notifications from user

    }

    public function userMap($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        return view('user.profile.map', compact('user'));
    }

    public function userTimeline($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        return view('user.profile.horizontal-timeline', compact('user'));
    }

    public function report($username)
    {
        $user = User::where('username',$username)->first();
        if($user->id){
            $reported = $user->reported;
            $reported++;
            $user->reported = $reported;
            $user->save();
            return \redirect()->back()->with('status', 'User reported!');
        }
        return \redirect()->back()->with('error', 'Missing User');
    }

    public function apiSearch($input){
        if(strlen($input)<3) return;

        $output = array();
        $output1 = User::where('first_name','LIKE', '%'.$input.'%')->get()->toArray();
        $output2 = User::where('last_name','LIKE', '%'.$input.'%')->get()->toArray();
        foreach ($output1 as $entry){
            $output[$entry['id']] = $entry;
        }
        foreach ($output2 as $entry){
            $output[$entry['id']] = $entry;
        }
        return json_encode($output);
    }

    public function checkEmail(Request $request){
        $user = User::where('email', '=', $request->get('email'))->first();
        if ($user === null) {
            return json_encode(array('taken'=>'false'));
        }
        return json_encode(array('taken'=>'true'));
    }
}
