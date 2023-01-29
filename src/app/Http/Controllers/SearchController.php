<?php

namespace App\Http\Controllers;

use App\Post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $results = collect([
            'users' => $this->getUsers($request),
            'posts' => $this->getPosts($request)
        ]);

        return view('user.search.index', compact('results'));
    }

    public function filters()
    {
        return view('user.search.filters');
    }

    private function parseInput(Request $request)
    {
        $data = collect([
            'search' => $request->get('q'),
            'filters' => collect($request->get('filters')),
        ]);

        $data['filters'] = $data['filters']->map(function($filter) {
            if($filter) {
                return explode(',', str_replace(' ', '', $filter));
            }
        });

        return $data;
    }

    private function getUsers(Request $request)
    {
        $query = User::where('id', '!=', Auth::id())->take(10);
        $searchData = $this->parseInput($request);

        $names = explode(' ', $searchData['search']);

        $query->where(function($q) use ($names) {
            foreach (['first_name', 'last_name'] as $key => $field) {
                foreach ($names as $key2 => $name) {
                    if (!$key && !$key2) $q->where($field, 'like', "$name%");
                    else $q->orWhere($field, 'like', "$name%");
                }
            }
        });

        if(!empty($searchData['filters']['placeOfBirth'])) {
            $query->whereIn('location', $searchData['filters']['placeOfBirth']);
        }

        return $query->get();
    }

    private function getPosts(Request $request)
    {
        $query = Post::with('user', 'comments.user')->take(10);

        $searchData = $this->parseInput($request);

        $query->where('title', 'like', "%{$searchData['search']}%");

        if(!empty($searchData['filters']['codexLocation'])) {
            $query->whereIn('location', $searchData['filters']['codexLocation']);
        }

        if(!empty($searchData['filters']['placeOfBirth'])) {
            $query->whereHas('user', function($q) use($searchData) {
                $q->whereIn('location', $searchData['filters']['placeOfBirth']);
            });
        }

        return $query->get();
    }
}
