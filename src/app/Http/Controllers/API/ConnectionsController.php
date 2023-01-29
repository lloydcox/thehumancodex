<?php
/**
 * Created by PhpStorm.
 * User: szyman
 * Date: 05.12.18
 * Time: 19:02
 */

namespace App\Http\Controllers\API;

use App\Connection;
use App\ConnectionCategory;
use App\ConnectionGrouping;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConnectionsController
{
    public function connectionsList(User $user)
    {
        return [
            'message' => '',
            'status' => 'success',
            'data' => Connection::allConnectedTo($user),
        ];
    }

    public function removeConnection(User $user, Request $request) {

        $authUser = $request->user();

        Connection::removeConnectionsBetween($authUser, $user);

        return [
            'message' => 'Connection was removed!',
            'status' => 'success',
            'data' => Connection::allConnectedTo($authUser)
        ];
    }

    public function sendInvitation(Request $request)
    {
        $authUser = $request->user();
        $emails = collect($request->all());
        $users = User::whereIn('email', $emails)->get(['id', 'email']);
        foreach ($users as $user) {
            Connection::addConnectionBetween($authUser, $user);
        }

        foreach ($emails->diff($users->pluck('email')) as $email) {
            /*
            * TODO: Add email invitation to the app.
            */
        }

        return [
            'message' => 'Invitations were sent',
            'status' => 'success',
            'data' => null
        ];
    }


    public function connectionCategoryList(User $user, Request $request) {

        $authUser = $request->user();
        $connectionCategories = ConnectionCategory::all();


        if ($authUser->id === $user->id) {
            $connections = Connection::where('user_id', $authUser->id)
            ->with('groups.categories')->get();
            $finalConnectionGroupings = [];
            foreach ($connections as $key => $connection) {
                $connectionGroupings = $connection->groups;
                foreach ($connectionGroupings as $key => $connectionGrouping) {
                    array_push($finalConnectionGroupings, $connectionGrouping);
                }
            }
            $data['connectionCategories'] = $connectionCategories;
            $data['connectionGroupings'] = $connections;
        }else{
            $connection = Connection::where('user_id', $authUser->id)
            ->where('connected_user_id', $user->id)
            ->with('groups')->first();
            $connectionGroupings = $connection->groups;

            foreach ($connectionCategories as $key => $connectionCategory) {
                $found = false;
                foreach ($connectionGroupings as $key => $connectionGrouping) {
                if($connectionGrouping->connection_category_id === $connectionCategory->id){
                    $found = true;
                }
                }
                $connectionCategory->checked = $found;
            }
            
            $data['connectionCategories'] = $connectionCategories;
        }
        
        
        return [
            'message' => 'Connection categories retrieved!',
            'status' => 'success',
            'data' => $data,
        ];
    }

    public function connectionCategoryStore(Request $request){
        $connectedUser = request('connectedUser');
        $selectedConnectionCategories = request('selectedConnectionCategories');
        $selectedConnectionCategories = explode(", ",$selectedConnectionCategories);
        $authUser = $request->user();
        $connection = Connection::where('user_id', $authUser->id)->where('connected_user_id', $connectedUser)->first();

        $connectionGroupings = ConnectionGrouping::where('connection_id', $connection->id)->get();
        foreach ($connectionGroupings as $key => $connectionGrouping) {
            if ($connectionGrouping !== null) {
                $connectionGrouping->delete();
            }
            
        }
        foreach ($selectedConnectionCategories as $key => $selectedConnectionCategory) {
            if ((int)$selectedConnectionCategory !== 0) {
                $connectionGrouping = new ConnectionGrouping([
                    'connection_id' => $connection->id,
                    'connection_category_id' => (int)$selectedConnectionCategory,
                ]);
                $connectionGrouping->save();
            }
        }

        return [
            'message' => 'Connection categories updated!',
            'status' => 'success',
            'data' => null
        ];
    }

    /**
     * Get the connections that matched given connection ids
     * */
    public function getConnectionsByConnectionCategories(){
        if(request()->has('categories')){
            $connectionCategories = explode(',', request('categories'));
            $data = [];
            $loggedInUserId = Auth::id();
            if(count($connectionCategories) > 0){
                $connectedToUser = Connection::select('users.*')
                    ->join('users', 'connections.connected_user_id', 'users.id')
                    ->join('connection_groupings',  'connections.id', 'connection_groupings.connection_id')
                    ->where('connections.user_id', $loggedInUserId)
                    ->whereIn('connection_groupings.connection_category_id', $connectionCategories)->get();
                $userConnected = Connection::select('users.*')
                    ->join('users', 'connections.user_id', 'users.id')
                    ->join('connection_groupings',  'connections.id', 'connection_groupings.connection_id')
                    ->where('connections.connected_user_id', $loggedInUserId)
                    ->whereIn('connection_groupings.connection_category_id', $connectionCategories)->get();
                $data = $connectedToUser->merge($userConnected);
            }
            return [
                'message' => 'Connection categories updated!',
                'status' => 'success',
                'data' => $connectedToUser->merge($userConnected)
            ];
        }else{
            return [
                'message' => 'Connection categories required!',
                'status' => 'failed',
                'data' => []
            ];
        }
    }
}