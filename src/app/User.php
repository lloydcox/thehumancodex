<?php

namespace App;

use Carbon\Carbon;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'location', 'gender', 'age', 'email', 'password', 'username', 'validated', 'active', 'type', 'reported', 'email_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = [
        'age'
    ];

    protected $appends = ['data_list', 'avatar', 'description', 'is_connected_with_me'];

    public function data()
    {
        return $this->hasMany('App\UserData');
    }

    public function categoryInputs()
    {
        return $this->hasMany('App\UserCategoryInput');
    }

    public function connections()
    {
        return $this->hasMany('App\Connection');
    }

    public function posts()
    {
        return $this->hasMany('App\Post');
    }

    public function comments()
    {
        return $this->hasMany('App\Comments');
    }

    public function kudos()
    {
        return $this->hasMany('App\Kudos');
    }

    public function notifications()
    {
        return $this->hasMany('App\Notification');
    }

    public function causers()
    {
        return $this->morphMany('App\Notification', 'causer');
    }

    public function getDataListAttribute()
    {
        return $this->data()->get(['code', 'value'])->pluck('value', 'code');
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getAvatarAttribute()
    {
        if (empty($this->data_list['avatar'])) {
            return '/images/profile_placeholder.png';
        }

        return $this->data_list['avatar'];
    }

    public function getDescriptionAttribute()
    {
        if (empty($this->data_list['description'])) {
            return '';
        }

        return $this->data_list['description'];
    }

    public function getIsConnectedWithMeAttribute()
    {
        return Connection::isConnected($this);
    }
}
