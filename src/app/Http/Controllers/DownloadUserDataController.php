<?php

namespace App\Http\Controllers;

use App\Post;
use App\PostCategory;
use App\UserDataDownloadRequest;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ZipArchive;

class DownloadUserDataController extends Controller
{
    /**
     * View download your data page
     */
    public function show(){
        return view('user.gdpr.download_data');
    }

    /**
     * Download user requested data to a PDF
     * @throws \Exception
     */
    public function printPDF(){

        $pdfDirName = public_path().'/PDFs';
        $zipDirName = public_path().'/ZIPs';
        $dirNames = [$pdfDirName, $zipDirName];
        foreach ($dirNames as $dirName){
            if (!is_dir($dirName)) {
                mkdir($dirName);
            }
        }

        $requestedCategories = [];

        $limit = Carbon::now()->subDay(1);
        $existingFile = UserDataDownloadRequest::where('created_at', '>', $limit)
            ->where('user_id', Auth::id())->first();

        if (!empty($existingFile)){
            $zip = $existingFile->zip_file;
            if (file_exists(public_path($zip))){
                unlink(public_path($zip));
            }
            $existingFile->delete();
        }


        if (request()->has('download_data_moments')){
            $posts = Auth::user()->posts()->with('postCategory', 'comments.user', 'kudos.user', 'user')
                ->orderBy('updated_at', 'DESC')->get();
            $groupedResult = [];
            $tempDates = [];
            foreach ($posts as $post){
                $date = Carbon::parse($post->date)->format('l jS \\of F Y');
                if(!in_array($date, $tempDates)){
                    array_push($tempDates, $date);
                    $groupedResult[$date] = [
                        $post
                    ];
                }else{
                    array_push($groupedResult[$date], $post);
                }
            }
            // This  $data array will be passed to our PDF blade
            $momentsData = [
                'posts' => $groupedResult
            ];

            $pdf = PDF::loadView('user.gdpr.download_moments', $momentsData);
            $output = $pdf->output();
            file_put_contents('PDFs/moments.pdf', $output);
            array_push($requestedCategories, 'Moments');
        }

        if (request()->has('download_data_comments')){

            $comments = Auth::user()->comments()->with('user')
                ->orderBy('updated_at', 'DESC')->get();
            $groupedResult = [];
            $tempDates = [];
            foreach ($comments as $index => $comment){
                $date = Carbon::parse($comment->updated_at)->format('l jS \\of F Y');
                $post = Post::where('id', $comment->post_id)->with('postCategory', 'comments.user', 'kudos.user', 'user')->first();
                if ($post){

                    $comments[$index]->post = $post;
                    if(!in_array($date, $tempDates)){
                        array_push($tempDates, $date);
                        $groupedResult[$date] = [
                            $comment
                        ];
                    }else{
                        array_push($groupedResult[$date], $comment);
                    }
                }
            }

            $baseURL = url('/');
            $commentsData = [
                'comments' => $groupedResult,
                'baseURL' => $baseURL
            ];

            $pdf = PDF::loadView('user.gdpr.download_comments', $commentsData);
            $output = $pdf->output();
            file_put_contents('PDFs/comments.pdf', $output);
            array_push($requestedCategories, 'Comments');
        }

        if (request()->has('download_data_kudos')){

            $kudos = Auth::user()->kudos()->with('user')
                ->orderBy('updated_at', 'DESC')->get();
            $groupedResult = [];
            $tempDates = [];
            foreach ($kudos as $index => $kudosItem){
                $date = Carbon::parse($kudosItem->updated_at)->format('l jS \\of F Y');
                $post = Post::where('id', $kudosItem->post_id)->with('postCategory', 'comments.user', 'kudos.user', 'user')->first();
                if ($post){
                    $kudos[$index]->post = $post;
                    if(!in_array($date, $tempDates)){
                        array_push($tempDates, $date);
                        $groupedResult[$date] = [
                            $kudosItem
                        ];
                    }else{
                        array_push($groupedResult[$date], $kudosItem);
                    }
                }
            }

            $baseURL = url('/');
            $kudosData = [
                'kudos' => $groupedResult,
                'baseURL' => $baseURL
            ];

            $pdf = PDF::loadView('user.gdpr.download_kudos', $kudosData);
            $output = $pdf->output();
            file_put_contents('PDFs/kudos.pdf', $output);
            array_push($requestedCategories, 'Kudos');
        }

        if (request()->has('download_data_profile')){

            $profile = Auth::user()->where('id', Auth::id())->with('data', 'categoryInputs')->first();

            $publicPath = public_path();
            $profileData = [
                'profile' => $profile,
                'public_path' =>$publicPath
            ];

            $pdf = PDF::loadView('user.gdpr.download_profile', $profileData);
            $output = $pdf->output();
            file_put_contents('PDFs/profile.pdf', $output);
            array_push($requestedCategories, 'Profile');
        }

        $zip_file = $zipDirName.'/'.Auth::id().'-User-Data.zip';
        $zip = new \ZipArchive();
        $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        $path = public_path('PDFs');
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
        foreach ($files as $name => $file)
        {
            // We're skipping all sub-folders
            if (!$file->isDir()) {
                $filePath     = $file->getRealPath();

                // extracting filename with substr/strlen
                $relativePath = 'MyUserData/' . substr($filePath, strlen($path) + 1);

                $zip->addFile($filePath, $relativePath);
            }
        }
        $zip->close();

        $deleteFilePaths = ['PDFs/comments.pdf', 'PDFs/moments.pdf', 'PDFs/kudos.pdf', 'PDFs/profile.pdf'];
        foreach ($deleteFilePaths as $deleteFilePath){
            if (file_exists($deleteFilePath)){
                unlink($deleteFilePath);
            }
        }

        $requestedCategories = implode(', ', $requestedCategories);
        $databaseUpdate = [
            'user_id' => Auth::id(),
            'requested_categories' => $requestedCategories,
            'zip_file' => 'ZIPs/'.Auth::id().'-User-Data.zip',
            'status' => 'existing'
        ];


        if (!empty($requestedCategories)){
            UserDataDownloadRequest::create($databaseUpdate);
            return response()->download($zip_file);
        }else{
            return redirect()->back();
        }


    }
}
