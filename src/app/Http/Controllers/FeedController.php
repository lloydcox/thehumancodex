<?php

namespace App\Http\Controllers;

use App\Comments;
use App\Connection;
use App\Post;
use App\PostCategory;
use App\PostViewed;
use App\Services\Google\GoogleGeocodeService;
use App\Social;
use App\User;
use App\UserCategoryInput;
use App\UserData;
use App\ViewPost;
use Carbon\Carbon;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\PostDec;
use Storage;
use Image;
use App\Charts\MomentsByCategory;

class FeedController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function timeline()
    {
        $this->getBasicData();
        $data = $this->data;

        $user_id = Auth::user()->id;

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

        return view('timeline',$data);
    }

    public function apiTimeline(User $user, Request $request)
    {
        $location= DB::table('posts')->select('location',DB::raw('count(*)as total'))->where('user_id',$user['id'])->groupBy('location')->get();
        $query = Post::with('comments.user', 'kudos.user', 'user')
            ->where('user_id','=', $user->id)
            ->orderBy('date', 'desc');

        $query = $this->timelineScope($query, $request);

        // Collect post data
        $posts = $query
            ->get(['id', 'content', 'title', 'location', 'date', 'user_id', 'image', 'lat', 'lng', 'youtube_url']);

        $posts = $posts->sortByDesc('date')->values();

        $selectedCategory = null;
        //if need to show any special category posts
        if($request->has('specially_requested_category')){
            $posts = Post::where('post_category_id', $request->specially_requested_category)
                ->where('user_id', $user->id)
                ->with('comments.user', 'kudos.user', 'user', 'postCategory')
                ->get();
            $selectedCategory = PostCategory::where('id', $request->specially_requested_category)->first();
        }

        $posts=array_values($posts->toArray());
        return response()->json([
            'status' => 'success',
            'message' => '',
            'data' => $posts,
            'location'=>$location,
            'selectedCategory' => $selectedCategory
        ]);
    }

    public function apiTimelineUser(User $user, Request $request)
    {

        $query = Post::with('comments.user', 'kudos.user', 'user')
            ->where('user_id','=', $user->id)
            ->orderBy('date', 'desc');

        $query = $this->timelineScope($query, $request);

        // Collect post data
        $posts = $query
            ->get(['id', 'content', 'title', 'location', 'date', 'user_id', 'image', 'lat', 'lng']);

        $posts = $posts->sortByDesc('date')->values();
        return response()->json([
            'status' => 'success',
            'message' => '',
            'data' => $posts

        ]);
    }

    public function profile()
    {
        $this->getBasicData();
        $data = $this->data;

        $user_id = Auth::user()->id;

        $auth_user = UserData::where('user_id', '=',$user_id)->where('code', '=', 'image')->first();
        if($auth_user) {
            $data['user_image'] = $auth_user->value;
        }

        //Collect user data
        $user = User::findOrFail($user_id);
        $data['user'] = $user;

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
        $posts = Post::where('user_id','=', $user_id)->orderBy('updated_at', 'DESC')->get();
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
        $momentsByCategory->height('250');
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

    public function activity()
    {
        $this->getBasicData();
        $data = $this->data;

        $user_id = Auth::user()->id;


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
        $connections = Connection::where('user_id', $user_id)
            ->orWhere('connected_user_id', $user_id)
            ->orderBy('updated_at', 'DESC')
            ->get();
        foreach ($connections as $connection){
            $data['activity'][(string)$connection->updated_at] = $connection;
        }

        $posts = Post::where('user_id','=', $user_id)->orderBy('updated_at', 'DESC')->get();
        foreach ($posts as $post){
            $data['activity'][(string)$post->updated_at] = $post;
        }
        $comments = Comments::where('user_id','=', $user_id)->orderBy('updated_at', 'DESC')->get();
        foreach ($comments as $comment){
            $data['activity'][(string)$comment->updated_at] = $comment;
        }

        return view('activity', $data);
    }

    public function apiMapPublic() {

        $user = Auth::user();
//        dd($user);
        $user_id = $user->id;
        //Collect post data
        $connections_ids1 = Connection::where('user_id', $user_id)->pluck('connected_user_id')->toArray();
        $connections_ids2 = Connection::where('connected_user_id', $user_id)->pluck('user_id')->toArray();
        $userArray = array($user_id);
        $connections_ids = array_merge($connections_ids1, $connections_ids2, $userArray);
        $posts = Post::with('comments.user', 'kudos.user', 'user')->whereIn('user_id',$connections_ids)->orderBy('updated_at', 'DESC')->get();

        $user_data = UserData::where('user_id','=',$user_id)->get();
        foreach ($user_data->toArray() as $entry) {
            if($entry['code']=='image'){
                $data['user_data']['avatar'] = $entry['value'];
            }else {
                $data['user_data'][$entry['code']] = $entry['value'];
            }
        }

        foreach ($posts as $key => $post) {
            $post->is_new = isset($user->getDataListAttribute()['last_visit']) ?
                ($user->getDataListAttribute()['last_visit'] < $post->created_at && $user->id != $post->user_id) :
                true;
            $posts[$key] = $post;
        }

        UserData::updateOrCreate([
            'code' => 'last_visit',
            'user_id' => $user_id
        ],[
            'value' => Carbon::now()
        ]);

        $data['posts'] = $posts;
        return json_encode($data);
    }

    public function apiMap(){
        $user_id = Auth::user()->id;
        //Collect post data
        $connections_ids = array($user_id);
        $posts = Post::with('comments.user', 'kudos.user', 'user')->whereIn('user_id',$connections_ids)->orderBy('updated_at', 'DESC')->get();
        $data['posts'] = $posts;
        return json_encode($data);
    }

    public function publicMap()
    {
        $user_id = Auth::user()->id;

        //Collect user data
        $user = User::findOrFail($user_id);

        $auth_user = UserData::where('user_id', '=',$user_id)->where('code', '=', 'image')->first();
        if($auth_user) {
            $data['user_data']['avatar'] = $auth_user->value;
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
        $userArray = array($user_id);
        $connections_ids = array_merge($connections_ids1, $connections_ids2, $userArray);
        $posts = Post::whereIn('user_id',$connections_ids)->orderBy('updated_at', 'DESC')->get();
        $data['posts'] = $posts;

        return view('map', $data);
    }

    public function post(Request $request, GoogleGeocodeService $geocodeService)
    {
        $user_id = Auth::user()->id;
        $content = $request->get('content');
        $title = $request->get('title');
        $youtube_url = $request->get('youtube_url');
        $location = $request->get('location');
        $date = $request->get('date');
        $image =  $request->get('image');
        $postCategory = $request->get('category');
        //dd($youtube_url);

        try {
            $coords = $geocodeService->find($location);
        } catch(\Exception $e) {
            dd($e);
            return response([
                'status' => 'error',
                'message' => "We can not find location '$location' on the map. Be more precise.",
                'data' => []
            ], 422);
        }

        try {
            if ($image) {
                $image_path = $this->storePostImage($image);
            }

            $post = Post::create([
                'user_id' => $user_id,
                'title' => $title,
                'youtube_url' => $youtube_url,
                'content' => $content,
                'location' => $coords['location'],
                'date' => Carbon::createFromFormat('Y-m-d', $date),
                'lat' => $coords['lat'],
                'lng' => $coords['lng'],
                'post_category_id' => $postCategory,
                'image' => !empty($image_path) ? "/storage/$image_path" : null
            ]);
        }catch (\Illuminate\Database\QueryException $e){
            return response([
                'status' => 'error',
                'message' => 'Make sure you fill required fields!',
                'data' => $e->getMessage()
            ], 422);
        }
        $this->createNotification(Auth::user(),$post,Auth::user()->id, 'add',$post->id);

        $post->user = Auth::user();
        $post->date = $post->date->format('Y-m-d h:i:s');

        return response([
            'status' => 'success',
            'message' => 'Moment was added to your timeline!',
            'data' => $post
        ], 201);
    }

    public function update(Post $post, request $request, GoogleGeocodeService $geocodeService)
    {
        $content = $request->get('content');
        $title = $request->get('title');
        $location = $request->get('location');
        $youtube_url = $request->get('youtube_url');
        $date = $request->get('date');
        $image =  $request->get('image');
        $category =  $request->get('category');

        if($request->user()->id !== $post->user_id){
            return response([
                'status' => 'error',
                'message' => "You are not allowed to to this.",
                'data' => []
            ], 422);
        }

        try {
            $coords = $geocodeService->find($location);
        } catch(\Exception $e) {
            return response([
                'status' => 'error',
                'message' => "We can not find location '$location' on the map. Be more precise.",
                'data' => []
            ], 422);
        }

        try {
            if ($image) {
                $oldImagePath = $post->image;
                $image_path = $this->storePostImage($image);
            }

            $post = $post->fill([
                'title' => $title,
                'youtube_url' => $youtube_url,
                'content' => $content,
                'location' => $coords['location'],
                'date' => Carbon::createFromFormat('Y-m-d', $date),
                'lat' => $coords['lat'],
                'lng' => $coords['lng'],
                'image' => !empty($image_path) ? "/storage/$image_path" : null,
                'post_category_id' => $category
            ]);

            $post->save();

        } catch (\Illuminate\Database\QueryException $e) {
            return response([
                'status' => 'error',
                'message' => 'Make sure you fill required fields!',
                'data' => $e->getMessage()
            ], 422);
        }

        if(!empty($oldImagePath)) {
            $path = str_replace('storage', 'public', $oldImagePath);
            if (Storage::exists($path)) {
                Storage::delete($path);
            }
        }

        return response([
            'status' => 'success',
            'message' => 'Moment was updated!',
            'data' => Post::with(['user', 'comments.user'])->find($post->id)
        ], 200);
    }

    private function storePostImage($image)
    {
        $user_id = Auth::user()->id;

        $fileName = time().".png";
        $image_path = "images/posts/{$user_id}/$fileName";

        $file = Image::make($image);

        $file->resize(null, 1200, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        Storage::disk('public')->put($image_path, (string) $file->encode());

        return $image_path;
    }

    public function remove(Post $post)
    {
        $user_id = Auth::user()->id;
        if($user_id == $post->user_id){
            $post->delete();
        }

        return response([
            'status' => 'success',
            'message' => 'Moment was removed!',
            'data' => $post
        ], 200);
    }


    public function apiFeed(Request $request)
    {

        $location= DB::table('posts')->select('location',DB::raw('count(*)as total'))->where('user_id','!=',Auth::id())->groupBy('location')->get();

        $user = $request->user();

        // Fetch connection posts
        $postsIds = $user->connections()->accepted()->with('invited.posts')->get()->pluck('invited.posts')->flatten()->pluck('id');

        $query = Post::whereIn('id', $postsIds);
        $query = $query->with('postCategory','comments.user', 'kudos.user', 'user');
        $query = $this->timelineScope($query, $request);
        $contactPosts = $query->get();

        // Fetch user posts and combine with others
        $query = $user->posts()->with('postCategory', 'comments.user', 'kudos.user', 'user');
        $query = $this->timelineScope($query, $request);
        $userPosts = $query->get();
        $posts = $userPosts->merge($contactPosts);

        // Sort
        $posts = $posts->sortByDesc('date')->values();
        $post_vieweds=ViewPost::getViewedPosts(Auth::id());

        foreach ($posts as $post){
            foreach ($post_vieweds as $post_viewed){
                if ($post['id'] === $post_viewed['post_id']){
                    $key=array_search($post->toArray(),$posts->toArray());
                    unset($posts[$key]);
                }
            }
        }

        if ($request->has('limit')) {
            $posts = $posts->take($request->get('limit'));
        }

        $posts=array_values($posts->toArray());

        //if need to show any special posts on top
        if($request->has('specially_requested_post')){
            $posts = Post::where('id', $request->specially_requested_post)
                ->with('comments.user', 'kudos.user', 'user', 'postCategory')
                ->whereIn('id', [0 =>$request->specially_requested_post])->get();
        }

        $selectedCategory = null;
        //if need to show any special category posts
        if($request->has('specially_requested_category')){
            $posts = Post::where('post_category_id', $request->specially_requested_category)
                ->where('user_id', Auth::id())
                ->with('comments.user', 'kudos.user', 'user', 'postCategory')
                ->get();
            $selectedCategory = PostCategory::where('id', $request->specially_requested_category)->first();
        }

        foreach ($posts as $key=>$post){
            $post['user_id'] === Auth::id() ?$posts[$key]['isOwn']=true:$posts[$key]['isOwn']=false;
        }


        return response()->json([
            'status' => 'success',
            'message' => '',
            'data' => $posts,
            'location'=>$location,
            'selectedCategory' => $selectedCategory
        ]);


    }

    public function usersPost(Request $request){

        $userId = Auth::id();
        $posts = Post::where(function($query) use ($userId) {
            $query->where('user_id', '=', $userId);
        })
            ->get();
        return response()->json([
            'status' => 'success',
            'message' => '',
            'data' => $posts
        ]);

    }

    public function profileMap(Request $request)
    {
        $user = $request->user();

        return view('user.profile.map', compact('user'));
    }

    private function timelineScope($query, $request)
    {
        if ($request->has(['ne', 'sw'])) {

            $ne = explode(',', $request->get('ne'));
            $sw = explode(',', $request->get('sw'));

            $query
                ->where('lat', '>=', $sw[0])
                ->where('lat', '<=', $ne[0])
                ->where('lng', '>=', $sw[1])
                ->where('lng', '<=', $ne[1]);
        }

        if ($request->has('limit')) {
            $query->take($request->get('limit'));
        }

        return $query;
    }
    /**
     * Mark Post as Viewed
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function markAsViewed(){
        try {
            ViewPost::create([
                'post_id'=>request('id'),
                'user_id'=>Auth::id()
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response([
                'status' => 'error',
                'message' => 'Post not marked as viewed!',
                'data' => $e->getMessage()
            ], 422);
        }
        $data=request()->all();
        $data['viewed']=true;
        return response([
            'status' => 'success',
            'message' => 'Post marked as viewed!',
            'data'=>$data
        ], 200);
    }

    public function markAllAsViewed(){
        $user_id=Auth::id();
        try {
            $user = User::where('username', request('name'))->first();
            if ($user === null){
                return response([
                    'status' => 'error',
                    'message' => 'Incorrect user name!',
                ], 422);
            }

            $user_posts=Post::where('user_id',$user['id'])
                ->whereNotIn('id',ViewPost::select('post_id')
                ->where('user_id',$user_id))
                ->get();
            foreach ($user_posts as $user_post) {
                ViewPost::create([
                    'post_id' => $user_post['id'],
                    'user_id' => $user_id
                ]);
            }
        }catch (\Illuminate\Database\QueryException $e){
            return response([
                'status' => 'error',
                'message' => 'All posts are not marked as viewed!',
                'data' => $e->getMessage()
            ], 422);
        }
        return response([
            'status' => 'success',
            'message' => 'All posts are marked as viewed!',
            'data'=>$user_posts
        ], 200);
    }
    /**
     * User data
     * @return \Illuminate\Http\JsonResponse
     */
    public function userData(Request $request){
        $posts=[];
        //$username=$param;
        $user=User::where('id',Auth::id())->get();
        $user_id=$user[0]['id'];
        $connection=Connection::where('user_id',Auth::id())->where('connected_user_id',$user_id)->first();
        if ($user_id === Auth::id() || $connection['accepted'] === 1 ) {
            $query = Post::with('comments.user', 'kudos.user', 'user')
                ->where('user_id', '=', $user_id)
                ->orderBy('date', 'desc');

            $query = $this->timelineScope($query, $request);

            // Collect post data
            $posts = $query
                ->get(['id', 'content', 'title', 'location', 'date', 'user_id', 'image', 'lat', 'lng']);

            $posts = $posts->sortByDesc('date')->values();
            $post_vieweds=ViewPost::getViewedPosts(Auth::id());

        //    foreach ($posts as $post){
        //        foreach ($post_vieweds as $post_viewed){
        //            if ($post['id'] === $post_viewed['post_id']){
        //                $key=array_search($post->toArray(),$posts->toArray());
        //                unset($posts[$key]);
        //            }
        //        }
        //    }
        }
        $posts=array_values($posts->toArray());
        return response()->json([
            'status' => 'success',
            'message' => '',
            'data' => $posts
        ]);
    }

}
