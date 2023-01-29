<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestNotification extends Model
{
    protected $fillable=[
        'user_id','connected_user_id','status'
     ];
}
