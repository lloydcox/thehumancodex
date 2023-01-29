<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConnectionCategory extends Model
{
    protected $fillable = [
        'title',
        'description',
        'color_code'
    ];

    public function connection()
    {
        return $this->belongsTo('App\Connection');
    }
}
