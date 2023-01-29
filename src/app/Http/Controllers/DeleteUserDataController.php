<?php

namespace App\Http\Controllers;

use App\Connection;
use App\Notification;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DeleteUserDataController extends Controller
{
    /**
     * View delete your account page
     */
    public function show(){
        return view('user.gdpr.delete_account');
    }
    /**
     * View delete your account page
     */
    public function showConfirmation(){
        return view('user.gdpr.delete_account_confirmation');
    }
    /**
     * Delete your account
     */
    public function delete(){

        $incomingData = Validator::make(request()->all(), [
            'password' => 'required|string|max:250',
        ]);

        $user = Auth::user()->where('id', Auth::id())->first();
        $inputPassword = request('password');
        $isMatched = Hash::check($inputPassword, $user->password);

        if ($incomingData->fails() || !$isMatched) {
            $incomingData->getMessageBag()->add('password', 'Password you entered is wrong!');
            $incomingData->errors()->add('from', 'ADD');
        }else{

            $user->comments()->delete();
            $user->kudos()->delete();
            $user->connections()->delete();
            $user->data()->delete();
            $user->categoryInputs()->delete();
            $user->notifications()->delete();

            $connections = Connection::where('connected_user_id', Auth::id())->get();
            foreach ($connections as $connection) {
                $connection->delete();
            }
            $notifications = Notification::where('notifyUser_id', Auth::id())->get();
            foreach ($notifications as $notification) {
                $notification->delete();
            }

            $posts = Post::where('user_id', Auth::id())->get();
            foreach ($posts as $post) {
                $post->kudos()->delete();
                $post->comments()->delete();
            }

            $user->posts()->delete();
            $user->delete();
        }




        return redirect()->back()->withErrors($incomingData);
    }
}
