<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCookieApproval extends Model
{
    protected $fillable = [
        'user_id',
        'client_ip',
        'status'
    ];
}
