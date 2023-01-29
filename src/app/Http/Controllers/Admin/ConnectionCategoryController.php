<?php

namespace App\Http\Controllers\Admin;

use App\ConnectionCategory;
use App\ConnectionGrouping;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ConnectionCategoryController extends Controller
{

    /**
     * Show admin connection categories page
     */
    public function show() {
        return view('admin.pages.connection_categories', [
            'connection_categories' => ConnectionCategory::all()
        ]);
    }

    /**
     * Show admin add new connection category page
     */
    public function showConnectionCategoryForm() {
        return view('admin.pages.add_new_connection_category');
    }

    /**
     * Show admin edit connection category page
     */
    public function showEditConnectionCategoryFrom($id) {
        $connectionCategory = ConnectionCategory::find((int)$id);
        return view('admin.pages.edit_connection_category', [
            'connectionCategory' => $connectionCategory
        ]);
    }

    /**
     * Add new connection category
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
            ConnectionCategory::create($toInsert);
           return redirect('/admin/connection-categories');
        }
    }

    /**
     * Edit Connection category
     */
    public function editConnectionCategory(){
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
            ConnectionCategory::where('id', request('id'))->update($toUpdate);
            return redirect('/admin/connection-categories');
        }
    }


    /**
     * Delete connection category
     */
    public function delete() {
        $activeConnectionCount = ConnectionGrouping::where('connection_category_id', request('id'))->count();
        if($activeConnectionCount == 0){
            ConnectionCategory::where('id', request('id'))->delete();
            return redirect('/admin/connection-categories');
        }else{
            return redirect()->back()->with('error', 'There are THC connections that use this category. Please edit instead!');
        }
    }
}
