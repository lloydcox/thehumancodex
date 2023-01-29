<?php

namespace App\Http\Controllers;

use App\Social;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SocialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Social  $social
     * @return \Illuminate\Http\Response
     */
    public function show(Social $social)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Social  $social
     * @return \Illuminate\Http\Response
     */
    public function edit(Social $social)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Social  $social
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user_id = Auth::user()->id;
        $posts = $request->all();
        $user_socials = Social::where('user_id','=',$user_id)->first();
        if($user_socials){
            $user_socials->twitter = $posts['user-twitter'];
            $user_socials->facebook = $posts['user-facebook'];
            $user_socials->google = $posts['user-google+'];
            $user_socials->linkedin = $posts['user-linkedin'];
            $user_socials->instagram = $posts['user-instagram'];
            $user_socials->youtube = $posts['user-youtube'];
            $user_socials->save();
        }else {
            Social::create(array(
                'user_id' => $user_id,
                'twitter' => $posts['user-twitter'],
                'facebook' => $posts['user-facebook'],
                'google' => $posts['user-google+'],
                'linkedin' => $posts['user-linkedin'],
                'instagram' => $posts['user-instagram'],
                'youtube' => $posts['user-youtube']
            ));
        }

        return redirect("/settings/")->with('status', 'Socials updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Social  $social
     * @return \Illuminate\Http\Response
     */
    public function destroy(Social $social)
    {
        //
    }
}
