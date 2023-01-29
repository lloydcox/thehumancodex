<?php

namespace App\Http\Controllers\Admin;

use App\Post;
use App\PostCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PostCategoryController extends Controller
{

    /**
     * Show admin post categories page
     */
    public function show() {
        return view('admin.pages.post_categories', [
            'post_categories' => PostCategory::all()
        ]);
    }

    /**
     * Show admin add new post category page
     */
    public function showPostCategoryForm() {
        return view('admin.pages.add_new_post_category');
    }

    /**
     * Show admin edit post category page
     */
    public function showEditPostCategoryFrom($id) {
        $postCategory = PostCategory::find((int)$id);
        return view('admin.pages.edit_post_category', [
            'postCategory' => $postCategory
        ]);
    }

    /**
     * Edit Post category
     */
    public function editPostCategory(){
        $incomingData = Validator::make(request()->all(), [
            'title' => 'required|string|max:250',
            'description' => 'nullable|string|max:250',
            'color_code' => 'required|string'
        ]);
        if ($incomingData->fails()) {
            return  redirect()->back()->withErrors($incomingData);
        }else{
            $toUpdate = [
                'title' => request('title'),
                'description' => request('description') ? request('description') : null,
                'color_code' => request('color_code'),
            ];
            PostCategory::where('id', request('id'))->update($toUpdate);
            return redirect('/admin/post-categories');
        }
    }

    /**
     * Add new post category
     */
    public function store() {
        $incomingData = Validator::make(request()->all(), [
            'title' => 'required|string|max:250',
            'description' => 'nullable|string|max:250',
            'color_code' => 'required|string'
        ]);
        if ($incomingData->fails()) {
            $incomingData->errors()->add('from', 'ADD');
            return  redirect()->back()->withErrors($incomingData);
        }else{
            $toInsert = [
                'title' => request()->has('title')? request('title') : null,
                'description' => request()->has('description')? request('description') : null,
                'color_code' => request()->has('color_code')? request('color_code') : null
            ];
           PostCategory::create($toInsert);
           return redirect('/admin/post-categories');
        }
    }

    /**
     * Delete post category
     */
    public function delete() {
        $activePostCount = Post::where('post_category_id', request('id'))->count();
        if($activePostCount == 0){
            PostCategory::where('id', request('id'))->delete();
            return redirect('/admin/post-categories');
        }else{
            return redirect()->back()->with('error', 'There are THC posts that use this category. Please edit instead!');
        }
    }


}
