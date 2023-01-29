<?php

namespace App\Http\Controllers;

use App\Post;
use App\UserData;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Notification;
use App\Connection;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $data;

    public function loadNotifications()
    {
        if(Auth::id()) {
            $this->data['notifications'] = Notification::where('user_id', '=', Auth::id())->get();
        }
    }

    public function createNotification($causer, $target,$userId, $action,$post_id)
    {

        $user_id = Auth::id();

        if(isset($post_id) && $post_id !== null){
           $user_id = Post::where('id', $post_id)->first()->user_id;
           if(get_class($target) === 'App\Post'){
               $connections_list1 = Connection::where('user_id', $user_id)->pluck('connected_user_id')
                   ->toArray();
               $connections_list2 = Connection::where('connected_user_id', $user_id)->pluck('user_id')
                   ->toArray();
               $allConnections = array_unique(array_merge($connections_list1, $connections_list2));
               foreach ($allConnections as $connection){
                   $this->saveNotification($connection, $causer, $target,$userId, $action, $post_id);
               }
           }
        }

        if($user_id !== $userId){
            $this->saveNotification($user_id, $causer, $target,$userId, $action, $post_id);
        }
    }

    private function saveNotification($user_id, $causer, $target,$userId, $action, $post_id){
        Notification::create(array(
            'user_id' => $user_id,
            'notifyUser_id' => $userId,
            'post_id' => $post_id,
            'causer_id' => $causer->id,
            'causer_type' => get_class($causer),
            'target_id' => $target->id,
            'target_type' => get_class($target),
            'action' => $action
        ));
    }

    public function getBasicData(){
        $data = array();
        if(!Auth::id()) return $this->data = $data;
        $auth_user = UserData::where('user_id', '=',Auth::id())->where('code', '=', 'image')->first();
        if($auth_user) {
            $data['user_image'] = $auth_user->value;
        }
        $this->data = $data;
        $this->loadNotifications();
    }
}
