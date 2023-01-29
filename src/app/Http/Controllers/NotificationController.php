<?php

namespace App\Http\Controllers;

use App\Notification;
use Illuminate\Http\Request;
use App\RequestNotification;
use App\Connection;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Integer;
use Psy\Util\Json;

class NotificationController extends Controller
{

    /**
     * Show activities
     * @return view
     * @param request -> mark_as_read optional
     * @param request -> mark_all_as_read optional
     * -----------------------------------------------------------------------------------------------------------------
     */
    public function index()
    {

        // updating single notification status as read
        if(request()->has('mark_as_read')){
            Notification::where('id', request('mark_as_read'))->update(['status' => 'read']);
        }

        // updating all unread notifications of currently logged in user, to read
        if(request()->has('mark_all_as_read') && request('mark_all_as_read') ==  true){
            request()->user()
                ->notifications()
                ->where('status', 'unread')
                ->update(['status' => 'read']);
        }

        // get all unread notifications of currently logged in user
        $notifications = request()->user()
            ->notifications()
            ->where('status', 'unread')
            ->orderBy('created_at', 'desc')->get();

        return view('user.activity', compact('notifications'));
    }


    /**
     * Get the notifications count
     * @return Integer
     * -----------------------------------------------------------------------------------------------------------------
     */
    public function countOfNotification()
    {
        $activitiesCount = request()->user()
            ->notifications()
            ->where('status', 'unread')->count();

        $connectionRequestsCount = Connection::where('connected_user_id', Auth::id())
            ->where('accepted', (int)0)->count();

        //todo:need to implement connection accepted notifications count part

        return (int)$activitiesCount + (int)$connectionRequestsCount;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $return = null;
        $user_id = Auth::id();
        $notification = Notification::findorFail($id);
        if ($notification->user_id == $user_id) {
            $return[] = $notification->delete();
        }
        return json_encode($return);
    }
}
