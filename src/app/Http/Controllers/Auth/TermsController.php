<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;

class TermsController extends Controller
{
    public function showTermsForm()
    {
        $user = $this->getUser();

        return view('auth.terms', compact('user'));
    }

    public function showDeclineForm()
    {
        $user = $this->getUser();

        return view('auth.decline', compact('user'));
    }

    public function accept($token)
    {
        $user = $this->getUser();

        $user->active = 1;

        if($user->save()){
            return redirect('/register/cookies/'.$token);
        }
    }

    public function decline()
    {
        $user = $this->getUser();

        $user->delete();

        return redirect('/profile');
    }

    private function getUser($token = null)
    {
        if (!$user = request()->user()) {
            abort(401);
        }

        return $user;
    }
}
