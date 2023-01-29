<?php

namespace App\Http\Controllers;

use App\Connection;
use App\Post;
use App\PostCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class AccessUserDataController extends Controller
{
    /**
     * View access your data page
     */
    public function show(){
        return view('user.gdpr.access_data');
    }

    /**
     * Show moments data page
     */
    public function showMoments() {
        $posts = Auth::user()->posts()->with('postCategory', 'comments.user', 'kudos.user', 'user')
            ->orderBy('updated_at', 'DESC')->get();
        $groupedResult = [];
        $tempDates = [];
        foreach ($posts as $post){
            $date = Carbon::parse($post->date)->format('Y-m-d');
            if(!in_array($date, $tempDates)){
                array_push($tempDates, $date);
                $groupedResult[$date] = [
                    $post
                ];
            }else{
                array_push($groupedResult[$date], $post);
            }
        }

        $allCategories = PostCategory::all();
        return view('user.gdpr.access_moments', [
            'moments' => json_encode($groupedResult),
            'categories' => $allCategories
        ]);
    }

    /**
     * Show comments data page
     */
    public function showComments() {
        $comments = Auth::user()->comments()->with('user')
            ->orderBy('updated_at', 'DESC')->get();
        $groupedResult = [];
        $tempDates = [];
        foreach ($comments as $index => $comment){
            $date = Carbon::parse($comment->updated_at)->format('Y-m-d');
            $post = Post::where('id', $comment->post_id)->with('postCategory', 'comments.user', 'kudos.user', 'user')->first();
            if ($post){

                $comments[$index]->post = $post;
                if(!in_array($date, $tempDates)){
                    array_push($tempDates, $date);
                    $groupedResult[$date] = [
                        $comment
                    ];
                }else{
                    array_push($groupedResult[$date], $comment);
                }
            }
        }

        $allCategories = PostCategory::all();
        return view('user.gdpr.access_comments', [
            'comments' => json_encode($groupedResult),
            'categories' => $allCategories
        ]);
    }

    /**
     * Show kudos data page
     */
    public function showKudos() {
        $kudos = Auth::user()->kudos()->with('user')
            ->orderBy('updated_at', 'DESC')->get();
        $groupedResult = [];
        $tempDates = [];
        foreach ($kudos as $index => $kudosItem){
            $date = Carbon::parse($kudosItem->updated_at)->format('Y-m-d');
            $post = Post::where('id', $kudosItem->post_id)->with('postCategory', 'comments.user', 'kudos.user', 'user')->first();
            if ($post){
                $kudos[$index]->post = $post;
                if(!in_array($date, $tempDates)){
                    array_push($tempDates, $date);
                    $groupedResult[$date] = [
                        $kudosItem
                    ];
                }else{
                    array_push($groupedResult[$date], $kudosItem);
                }
            }
        }

        $allCategories = PostCategory::all();
        return view('user.gdpr.access_kudos', [
            'kudos' => json_encode($groupedResult),
            'categories' => $allCategories
        ]);
    }

    /**
     * Show my connections
     */
    public function showConnections() {
        $connections = Auth::user()->connections()->accepted()->with('user')
            ->orderBy('updated_at', 'DESC')->get();
        $groupedResult = [];
        $tempDates = [];
        foreach ($connections as $index => $connection){
            $date = Carbon::parse($connection->updated_at)->format('Y-m-d');
            if(!in_array($date, $tempDates)){
                array_push($tempDates, $date);
                $groupedResult[$date] = [
                    $connection->user
                ];
            }else{
                array_push($groupedResult[$date], $connection->user);
            }
        }
        return view('user.gdpr.access_connections', [
            'connections' => json_encode($groupedResult)
        ]);
    }
}
