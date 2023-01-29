<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserData extends Model
{
    protected $fillable = [
        'user_id', 'code', 'value'
    ];
}
