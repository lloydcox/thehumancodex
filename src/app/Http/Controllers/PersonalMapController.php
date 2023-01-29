<?php

namespace App\Http\Controllers;

use App\Post;
use App\ViewPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PersonalMapController extends Controller
{
    /**
     * Show personal map for users
     * -----------------------------------------------------------------------------------------------------------------
     */
    public function index(){
        $user = Auth::user();

        $ownPostLocations= DB::table('posts')->select('location',DB::raw('count(*)as total'))
            ->where('user_id',Auth::id())->groupBy('location')->get();

        $query = $user->posts()->with('postCategory', 'comments.user', 'kudos.user', 'user');
        $userPosts = $query->get();

        $posts = $userPosts;
        $locations = $ownPostLocations;

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
        return view('user.profile.personal-map', [
            'data' => json_encode($result)
        ]);
    }
}
