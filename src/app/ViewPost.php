<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ViewPost extends Model
{
    protected $fillable = [
        'user_id', 'post_id'
    ];

    /**
     * Get Viewed post by user id
     * @param $user
     * @return ViewPost[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function getViewedPosts($user){
        return ViewPost::where('user_id',$user)->get();
    }
}
