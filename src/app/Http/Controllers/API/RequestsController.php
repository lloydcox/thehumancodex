<?php
/**
 * Created by PhpStorm.
 * User: szyman
 * Date: 05.12.18
 * Time: 19:02
 */

namespace App\Http\Controllers\API;

use App\Connection;
use App\Http\Controllers\Controller;
use App\Notification;
use App\User;
use Illuminate\Http\Request;
use App\RequestNotification;
use Illuminate\Support\Facades\Auth;

class RequestsController extends Controller
{
    public function requestsList(User $user)
    {
        $data = Connection::allRequestFor($user);

        return [
            'message' => '',
            'status' => 'success',
            'data' => $data
        ];
    }

    public function approveRequest(Connection $connection)
    {
        $res=$connection->update(['accepted' => true]);
        $conn = Connection::addConnectionBetween($connection->invited, $connection->sender, ['accepted' => true]);

        if($res){
           Notification::create([
               'user_id' => $connection->user_id,
               'notifyUser_id' => $connection->user_id,
               'post_id' => null,
               'causer_id' => $connection->connected_user_id,
               'causer_type' =>  get_class(Auth::user()),
               'target_id' => Auth::id(),
               'target_type' =>get_class($connection),
               'action' => 'add'
           ]);
        }

        return [
            'message' => 'You have a new connection! Nice!',
            'status' => 'success',
            'data' => Connection::allRequestFor($connection->invited)
        ];
    }

    public function declineRequest(Connection $connection)
    {
        $connection->delete();

        return [
            'message' => 'Request was decline',
            'status' => 'success',
            'data' => Connection::allRequestFor($connection->invited)
        ];
    }
}