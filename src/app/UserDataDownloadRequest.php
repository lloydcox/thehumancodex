<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDataDownloadRequest extends Model
{
    protected $fillable = [
        'user_id',
        'requested_categories',
        'zip_file',
        'status'
    ];
}
