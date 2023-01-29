<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{

    protected $fillable = [
        'user_id', 'content', 'title', 'youtube_url', 'location', 'date', 'lng', 'lat', 'image', 'post_category_id'
    ];

    protected $appends = ['short', 'url', 'unique_slug'];

    public function getUniqueSlugAttribute()
    {
        return str_slug($this->title, '-') . '-' . $this->id;
    }

    public function getShortAttribute()
    {
        return str_limit($this->content, 120);
    }

    public function getUrlAttribute()
    {
        if($this->id) {
            return url('profile') . "#{$this->unique_slug}";
        }
        
        return null;
    }

    /*******************
     *   Relationsips
     ******************/

    /**
     * Return post comments.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\Comments');
    }

    /**
     * Return post kudos.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function kudos()
    {
        return $this->hasMany('App\Kudos');
    }

    /**
     * Return post user.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Return post category.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function postCategory()
    {
        return $this->belongsTo('App\PostCategory');
    }
}
