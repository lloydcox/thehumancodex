<?php

namespace App\Console\Commands;

use App\UserDataDownloadRequest;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpiredRequestedUserDataFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expired:requested-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expired Requested User Data Files';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $limit = Carbon::now()->subDay(1);
        $expiredFiles = UserDataDownloadRequest::where('created_at', '<', $limit)->get();

        if (!empty($expiredFiles)){
            foreach ($expiredFiles as $expiredFile){
                $zip = $expiredFile->zip_file;
                if (file_exists(public_path($zip))){
                    unlink(public_path($zip));
                    $expiredFile->status = 'deleted';
                }
            }
        }

    }
}
