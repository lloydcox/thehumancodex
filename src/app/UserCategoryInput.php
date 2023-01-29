<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCategoryInput extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'post_category_id',
        'input'
    ];

}
