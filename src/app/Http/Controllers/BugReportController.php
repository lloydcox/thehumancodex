<?php

namespace App\Http\Controllers;

use App\BugAttachment;
use App\BugReport;
use App\Helpers\Common;
use App\Mail\SendBug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use \Validator;

class BugReportController extends Controller
{
    private $common;


    /**
     * PostAdvertisementController constructor function
     * @param Common $common
     * @param ImageManager $imageManager
     */
    public function __construct(
        Common $common
    )
    {
        $this->common = $common;
    }

    /**
     * Display the specified resource.
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('bugs.bugs');
    }

    /**
     * Store a newly created resource in storage.
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attachments=['image1'=>null,'image2'=>null,'image3'=>null];
        $data = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string'
        ]);
        if ($data->fails()) {
            return redirect('bugs.bugs')
                ->withErrors($data)->withInput();
        }
        $bugData = BugReport::create([
            'title' => $request['title'],
            'description' => $request['description']
        ]);

//        $current_time = Carbon::now()->timestamp;
        if (sizeof(request('images')) > 0) {
            if (array_key_exists(0, request('images'))) {
                $request->request->add(['image1' => request('images')[0]]);
            }
            if (array_key_exists(1, request('images'))) {
                $request->request->add(['image2' => request('images')[1]]);
            }
            if (array_key_exists(2, request('images'))) {
                $request->request->add(['image3' => request('images')[2]]);
            }
        }

        if ($bugData == true && request()->has('image1') || request()->has('image2') ||
            request()->has('image3')) {

            $expl = [
                'image1' => explode(',', request()->get('image1')),
                'image2' => explode(',', request()->get('image2')),
                'image3' => explode(',', request()->get('image3'))
            ];

            $dcode = [
                'image1' => request()->get('image1') != null ? base64_decode($expl['image1'][1]) : null,
                'image2' => request()->get('image2') != null ? base64_decode($expl['image2'][1]) : null,
                'image3' => request()->get('image3') != null ? base64_decode($expl['image3'][1]) : null
            ];

            $extension = [
                'image1' => (str_contains($expl['image1'][0], 'png')) ? 'png' : 'jpg',
                'image2' => (str_contains($expl['image2'][0], 'png')) ? 'png' : 'jpg',
                'image3' => (str_contains($expl['image3'][0], 'png')) ? 'png' : 'jpg'
            ];
            $title=$this->common->slugify(request('title'));
            $fileName = [
                'image1' => $dcode['image1'] ?  $title. '_1' . '.' . $extension['image1'] : null,
                'image2' => $dcode['image2'] ? $title . '_2' . '.' . $extension['image2'] : null,
                'image3' => $dcode['image3'] ? $title . '_3' . '.' . $extension['image3'] : null
            ];
            $filePath = [
                'image1' => public_path('images/bugs') . '/' . $fileName['image1'],
                'image2' => public_path('images/bugs') . '/' . $fileName['image2'],
                'image3' => public_path('images/bugs') . '/' . $fileName['image3']
            ];

            $dcode['image1'] == true ? file_put_contents($filePath['image1'], $dcode['image1']) : null;
            $dcode['image2'] == true ? file_put_contents($filePath['image2'], $dcode['image2']) : null;
            $dcode['image3'] == true ? file_put_contents($filePath['image3'], $dcode['image3']) : null;

            $attachments = BugAttachment::create([
                'bug_id' => $bugData['id'],
                'image1' => $fileName['image1'],
                'image2' => $fileName['image2'],
                'image3' => $fileName['image3']
            ]);
        }
            $this->sendMail($bugData, $attachments);

            return response()->json([
                'attachments' => $attachments,
                'report' => $bugData,
                'message' => 'Bug report successfully !'
            ]);

    }

    /**
     * Sending an email to the admin about the bug.
     * @param $bugData ,$attachments
     */
    public function sendMail($bugData, $attachments)
    {
        Mail::to(env('ADMIN_EMAIL'))->send(new SendBug($bugData, $attachments['image1'],
                $attachments['image2'], $attachments['image3'])
        );
    }
}
