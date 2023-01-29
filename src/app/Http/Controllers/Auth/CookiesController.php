<?php

namespace App\Http\Controllers\Auth;

use App\UserCookieApproval;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CookiesController extends Controller
{
    public function showCookiesForm()
    {
        $user = $this->getUser();

        return view('auth.cookies', compact('user'));
    }

    public function acceptCookies(Request $request)
    {
        $user = $this->getUser();
        $ip = $request->ip();

        $user->active = 1;

        $cookieApproval = new UserCookieApproval([
            'user_id' => $user->id,
            'client_ip'=> $ip,
            'status' => 'Agreed'
        ]);
        $cookieApproval->save();

        if($user->save()){
            return redirect('/setup');
        }
    }


    private function getUser($token = null)
    {
        if (!$user = request()->user()) {
            abort(401);
        }

        return $user;
    }


}
