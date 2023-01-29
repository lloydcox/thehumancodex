<?php

namespace App\Http\Controllers\API;

use App\UserCookieApproval;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CookieController extends Controller
{
    public function acceptCookies(Request $request)
    {
        try {
            $userCookieApproval = UserCookieApproval::where('user_id', Auth::id())->first();
            if (is_null($userCookieApproval)){
                $cookieApproval = new UserCookieApproval([
                    'user_id' => Auth::id(),
                    'client_ip'=> $request->ip(),
                    'status' => 'Agreed'
                ]);
                $cookieApproval->save();
            }else{
                $userCookieApproval->client_ip = $request->ip();
                $userCookieApproval->status = 'Agreed';
                $userCookieApproval->save();
            }


        } catch (\Exception $exception) {
            return response([
                'message' => "Something bad happened!",
                'status' => 'error',
                'data' => null
            ], 422);
        }

        return response([
            'message' => 'Cookie Approval Updated',
            'status' => 'success',
            'data' => null
        ]);
    }
}
