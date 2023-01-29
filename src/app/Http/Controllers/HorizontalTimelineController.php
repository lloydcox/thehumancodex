<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HorizontalTimelineController extends Controller
{
    public function index(){
       
        return view('user.profile.horizontal-timeline');
    }
}
