<?php

namespace App\Http\Controllers;

use App\PostCategory;
use App\UserCategoryInput;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        return view('user.settings.index');
    }

    public function profile()
    {
        $postCategoryInputs = UserCategoryInput::where('user_id', Auth::id())->get();
        $postCategories = PostCategory::all();
        foreach ($postCategories as $index => $postCategory){
            $postCategories[$index]->input = null;
            foreach ($postCategoryInputs as $postCategoryInput){
                if($postCategoryInput->post_category_id == $postCategory->id){
                    $postCategories[$index]->input = $postCategoryInput->input;
                }
            }
        }
        return view('user.settings.profile', [
            'postCategories' => $postCategories
        ]);
    }

    public function account()
    {
        return view('user.settings.account');
    }

    public function notification()
    {
        return view('user.settings.notification');
    }

    public function email()
    {
        return view('user.settings.email');
    }

    public function password()
    {
        return view('user.settings.password');
    }
}
