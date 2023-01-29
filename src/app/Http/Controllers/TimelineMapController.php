<?php

namespace App\Http\Controllers;

use App\Post;
use App\User;
use App\ViewPost;
use App\Connection;
use App\ConnectionCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TimelineMapController extends Controller
{
    /**
     * Show timeline map for users
     * -----------------------------------------------------------------------------------------------------------------
     */
    public function index(){
        $user = Auth::user();
        $postsIds = $user->connections()->accepted()->with('invited.posts')->get()->pluck('invited.posts')->flatten()
            ->pluck('id');

        $connections = Connection::where('user_id', $user->id)->with('user', 'groups.categories')->get();
        $connectionCategories = ConnectionCategory::all();

        $ownPostLocations= DB::table('posts')->select('location',DB::raw('count(*)as total'))
            ->where('user_id',Auth::id())->groupBy('location')->get();
        $connectionPostLocations = DB::table('posts')->select('location',DB::raw('count(*)as total'))
            ->whereIn('id', $postsIds)->groupBy('location')->get();

        $query = Post::whereIn('id', $postsIds);
        $query = $query->with('postCategory','comments.user', 'kudos.user', 'user');
        $contactPosts = $query->get();

        $query = $user->posts()->with('postCategory', 'comments.user', 'kudos.user', 'user');
        $userPosts = $query->get();

        $posts = $userPosts->merge($contactPosts);
        $locations = $ownPostLocations->merge($connectionPostLocations);

        $post_vieweds=ViewPost::getViewedPosts(Auth::id());

        $result = [];

        foreach ($locations as $index => $location){
            $locPosts = [];
            foreach ($posts as $post){
               if($location->location === $post->location){
                $postViewed = false;
                foreach ($post_vieweds as $post_viewed){
                    if ($post['id'] === $post_viewed['post_id']){
                        $postViewed = true;
                    }
                }
                if(!$postViewed){
                    $location->lat = $post->lat;
                    $location->lng = $post->lng;
                    array_push($locPosts, $post);
                }
               }
            }
            if(count($locPosts) > 0){
                $location->total = count($locPosts);
                array_push($result, [
                    'location' => $location,
                    'posts' => $locPosts
                ]);
            }
        }
        
        return view('user.map', [
            'data' => json_encode($result),
            'connections' => $connections,
            'connectionCategories' => $connectionCategories
        ]);
    }
    /**
     * Get selected connected user's posts
     * -----------------------------------------------------------------------------------------------------------------
     */
    public function getConnectionPosts($connectionId){
        
        $user = User::where('id', $connectionId)->first();

        $ownPostLocations= DB::table('posts')->select('location',DB::raw('count(*)as total'))
            ->where('user_id',$user->id)->groupBy('location')->get();

        $query = $user->posts()->with('postCategory', 'comments.user', 'kudos.user', 'user');
        $userPosts = $query->get();

        $posts = $userPosts;
        $locations = $ownPostLocations;

        $post_vieweds=ViewPost::getViewedPosts($user->id);

        $result = [];

        foreach ($locations as $index => $location){
            $locPosts = [];
            foreach ($posts as $post){
               if($location->location === $post->location){
                $postViewed = false;
                foreach ($post_vieweds as $post_viewed){
                    if ($post['id'] === $post_viewed['post_id']){
                        $postViewed = true;
                    }
                }
                if(!$postViewed){
                    $location->lat = $post->lat;
                    $location->lng = $post->lng;
                    array_push($locPosts, $post);
                }
               }
            }
            if(count($locPosts) > 0){
                $location->total = count($locPosts);
                array_push($result, [
                    'location' => $location,
                    'posts' => $locPosts
                ]);
            }
        }
        return response()->json([
            'status' => 'success',
            'message' => '',
            'data' => $result
        ]);
    }
    
}
