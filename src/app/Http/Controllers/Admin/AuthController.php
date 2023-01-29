<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    /**
     * Show admin login page
     */
    public function show(){
        return  Auth::check() && (Auth::user()->type === config('constance.user_types')['ADMIN']) ?
            redirect('admin/post-categories') : view('admin.pages.login');
    }

    /**
     * Log in admin user
     */
    public function authenticate() {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            if($user->type === config('constance.user_types')['ADMIN']){
                return redirect('admin/post-categories');
            }else{
                return redirect()->back()->with('error', 'You are not an admin!');
            }
        }else{
            return redirect()->back()->with('error', 'Invalid credentials!');
        }
    }

    /**
     * Log out an admin user
     */
    public function logout() {
        Auth::guard()->logout();
        request()->session()->invalidate();
        return redirect('/admin/login');
    }

}
