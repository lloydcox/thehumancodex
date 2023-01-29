<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class SetupController extends Controller
{
    public function index()
    {
        return view('user.setup.index');
    }

    public function contacts(Request $request)
    {
        $user = $request->user();

        $usersNearby = User::take(10)->get();

        $userId = $user->id;
        $sharingLink = url("/register?connect_with=$userId");

        return view('user.setup.contacts', compact('user', 'usersNearby', 'sharingLink'));
    }

    public function moment(Request $request)
    {
        $user = $request->user();

        return view('user.setup.moment', compact('user'));
    }

    public function contactsSearch(Request $request)
    {
        $user = $request->user();

        return view('user.setup.search', compact('user'));
    }

    public function contactsResults()
    {
        $users = User::take(30)->get();
        $results = collect();

        foreach ($users as $user) {
            if(rand(0, 1)) {
                $user->id = null;
            }
            $results->push($user);
        }

        return view('user.setup.results', compact('results'));
    }
}
