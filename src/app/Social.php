<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
    protected $fillable = [
        'user_id', 'twitter', 'facebook', 'google', 'linkedin','instagram', 'youtube'
    ];
}
