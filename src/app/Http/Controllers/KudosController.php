<?php

namespace App\Http\Controllers;

use App\Kudos;
use App\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KudosController extends Controller
{
    public function toggle($postId)
    {
        $user = Auth::user();
        $userId = $user->id;
        $data = [
            'user_id' => $userId,
            'post_id' => $postId,
        ];

    //delete kudo from notification table

        $notifiData=[
            'notifyUser_id' => $userId,
            'post_id' => $postId,
            'target_type'=> 'App\Kudos'
            ];
     
        if (Kudos::where($data)->exists() || Notification::where($notifiData)->exists()) {

            Kudos::where($data)->delete();
            Notification::where($notifiData)->delete();
          

            return response([
                'message' => 'Kudos was removed!',
                'status' => 'success',
                'data' => $user->id
            ], 200);
        }

        $kudos = Kudos::create($data);
        $this->createNotification(Auth::user(), $kudos,$userId,'add',$postId);

        $kudos->user = $user;

        return response([
            'message' => 'Kudos was added!',
            'status' => 'success',
            'data' => $kudos
        ], 201);

    }

}
