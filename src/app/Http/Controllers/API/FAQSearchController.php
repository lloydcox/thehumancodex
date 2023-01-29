<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FAQSearchController extends Controller
{
    /**
     * Show search result page with results
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        $resultset = [];
        if (request()->has('keyword')){
            foreach (config('constance.faq') as $faq => $item){
                foreach ($item as $keyset){
                    foreach ($keyset['key_word'] as $keyword){
                        if (preg_match('/\b'.\request('keyword').'\b/i', $keyword)){
                            array_push($resultset,['question'=>$keyset['question'],'answer'=>$keyset['answer'],'slug'=>$keyset['slug']]);
                        }

//                        echo strpos($keyword,request('keyword'));
//                        if(strpos($keyword,request('keyword')) == true){
////                            echo ($keyword.'==>'.\request('keyword'));
//                            array_push($resultset,['question'=>$keyset['question'],'answer'=>$keyset['answer'],'slug'=>$keyset['slug']]);
//                        }
                    }

//                    if (in_array(request()->get('keyword'), $keyset['key_word'])){
//                        array_push($resultset,['question'=>$keyset['question'],'answer'=>$keyset['answer'],'slug'=>$keyset['slug']]);
//                    }
                }
            }
            if (count($resultset) > 0){
                return view('public.search_results',[
                    'results'=>$resultset,
                    'keyword'=>request('keyword')
                ]);
            }else{
                return view('public.search_results',[
                    'results'=>null,
                    'keyword'=>request('keyword'),
                    'message'=>'No search results found'
                ]);
            }
        }else{
            return view('public.search_results',[
                'results'=>null,
                'keyword'=>request('keyword'),
                'message'=>'Search query not found'
            ]);
        }
    }

}
