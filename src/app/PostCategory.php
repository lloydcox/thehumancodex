<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{
    protected $fillable = [
        'title',
        'description',
        'color_code'
    ];

    public function post()
    {
        return $this->belongsTo('App\Post');
    }
}
