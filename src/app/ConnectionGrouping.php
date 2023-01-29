<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConnectionGrouping extends Model
{
    
    protected $fillable = [
        'connection_id',
        'connection_category_id'
    ];

    public function connection()
    {
        return $this->belongsTo('App\Connection');
    }

    public function categories()
    {
        return $this->belongsTo('App\ConnectionCategory', 'connection_category_id');
    }
}
