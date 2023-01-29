<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactUsController extends Controller
{
    public function show(){
        return view('public.contact');
    }
    /**
     * Send message to admin with bug report
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendMessage(){
        $name = request()->get('name');
        $email = request()->get('email');
        $message = request()->get('message');
        Mail::to(env('ADMIN_EMAIL'))->send(new ContactMail($name,$email,$message));
        return redirect()->back()->with('message','Message Sent Successfully !');
    }
}
