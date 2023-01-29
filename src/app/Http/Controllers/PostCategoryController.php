<?php

namespace App\Http\Controllers;

use App\PostCategory;
use Illuminate\Http\Request;

class PostCategoryController extends Controller
{

    /**
     * User data
     * @return \Illuminate\Http\JsonResponse
     */
    public function index() {
        $postCategories = PostCategory::all();
        return response()->json([
            'status' => 'success',
            'message' => '',
            'data' => $postCategories
        ]);
    }

}
