<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BugAttachment extends Model
{
    protected $fillable = [
        'bug_id', 'image1', 'image2','image3'
    ];
}
