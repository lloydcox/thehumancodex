<?php

namespace App\Http\Controllers;


use App\Connection;
use App\ConnectionCategory;
use App\Post;
use App\PostCategory;
use App\Social;
use App\UserCookieApproval;
use App\UserData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\User;
use App\Mail\DemoEmail;
use Illuminate\Http\Request;

class CmsController extends Controller
{
    public function about()
    {
        return view('public.about');
    }

    public function faq()
    {
        return view('public.faq');
    }

    public function legal()
    {
        return view('public.legal');
    }

    public function policy()
    {
        return view('public.policy');
    }

    public function terms()
    {
        return view('public.terms');
    }

    public function cookies()
    {
        return view('public.cookies');
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function rules()
    {
        return view('public.rules');
    }

    public function landing(Request $request)
    {
        $user = Auth::user();
        $postCategories = PostCategory::all();
        $connectionCategories = ConnectionCategory::all();
        $cookieBanner = false;

        if ($user) {
            $userCookieApproval = UserCookieApproval::where('user_id', $user->id)->first();
            if (is_null($userCookieApproval)){
                $cookieBanner = true;
            }elseif ($request->ip() !== $userCookieApproval->client_ip){
                $cookieBanner = true;
            }elseif ($userCookieApproval->status !== 'Agreed'){
                $cookieBanner = true;
            }
            //dd($cookieBanner);

            return view('user.index', compact('user', 'postCategories', 'cookieBanner', 'connectionCategories'));
        }

        return view('landing');
    }
}
