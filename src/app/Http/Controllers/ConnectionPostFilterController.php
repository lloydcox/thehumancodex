<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Post;

class ConnectionPostFilterController extends Controller
{
    public function filterPostsByConnections(Request $request){

        $user = $request->user();
        if(request()->has('usernames')){
            $userIds = explode(',', request('usernames'));
        }else{
            $userIds = [];
        }
        $posts = [];
        $locations = [];

        if(count($userIds) !== 0){
            foreach ($userIds as $key => $userId) {
                
                $location = DB::table('posts')->select('location',DB::raw('count(*)as total'))->where('user_id',$userId)->groupBy('location')->get();
                array_push($locations, $location);
                
                $query = Post::with('comments.user', 'kudos.user', 'user')
                    ->where('user_id','=', $userId)
                    ->orderBy('date', 'desc');
    
                $postsOfThisUser = $query
                    ->get(['id', 'content', 'title', 'location', 'date', 'user_id', 'image', 'lat', 'lng']);
    
                $postsOfThisUser = $postsOfThisUser->sortByDesc('date')->values();
                foreach ($postsOfThisUser as $key => $postOfThisUser) {
                    array_push($posts, $postOfThisUser);
                }
            }
        }
        
        return response()->json([
            'status' => 'success',
            'message' => '',
            'data' => $posts,
            'location'=>$locations,
            'usernames'=> $userIds
        ]);
    }

}
