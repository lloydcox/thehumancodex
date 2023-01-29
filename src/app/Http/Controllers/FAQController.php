<?php

namespace App\Http\Controllers;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FAQController extends Controller
{
    /**
     * show a faq page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showFaqPage(){
        $topQuestions = [];
        $i = 0;
        foreach (config('constance')['faq'] as $category){
            if($i < 3){
                $topQuestions[$i] = $category[$i];
            }
            $i++;
        }
        return view('public.faq', [
            'topQuestions' => $topQuestions,
        ]);
    }


    /**
     * show a answer Page
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showVideoPage(Request $request)
    {
        $slug = ($request->slug);
        foreach (config('constance')['faq'] as $item){
            $faqs=[];
            array_push($faqs,$item);
            for($i=0;$i<sizeof($faqs[0]);$i++){
                $index=$faqs[0][$i];
                if($slug === $index['slug']) {
                    if ($index['showVideo']===false) {
                        return view('public/faq_answers', [
                            'type'=>1,
                            'section' => $index['question'],
                            'answer' => $index['answer'],
                        ]);
                    }else{
                        return view('public/faq_answers',[
                            'type'=>2,
                            'section' => $index['question'],
                            'answer' => $index['video_path']
                        ]);
                    }
                }
            }

        }
    }
}
