<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Notification extends Model
{
    protected $fillable = [
        'user_id', 'notifyUser_id', 'post_id', 'causer_id', 'causer_type', 'target_id', 'target_type', 'action',
        'is_request_accepted'
    ];

    public function causer()
    {
        return $this->morphTo();
    }

    public function target()
    {
        return $this->morphTo();
    }

    public function getContentAttribute()
    {

        /**
         * activity notification of post
         */


        if ($this->causer_type == 'App\User') {
            if ($this->target_type == 'App\Post') {
                switch ($this->action) {
                    case 'add':
                        return "has added a new <a href='" . url("codex/{$this->causer->username}#{$this->target->unique_slug}") . "'>post</a> to his timeline!";
                        break;
                }
            }

            /**
             * activity notification of comments
             */


            if ($this->target_type == 'App\Comments') {
                switch ($this->action) {
                    case 'add':
                        $url = "/?view_notification_post=".$this->post_id;
                        return "has added a new comment to your <a href='{$url}'>post</a>!";
                        break;
                }
            }

            /**
             * activity notification of kudos
             */



            if ($this->target_type == 'App\Kudos') {
                switch ($this->action) {
                    case 'add':
                        $url = "/?view_notification_post=".$this->post_id;
                        return "has  Kudo to your <a href='{$url}'>post</a>!";
                        break;
                }
            }

            if ($this->target_type == 'App\Connection') {
                switch ($this->action) {
                    case 'add':
                        return "has accepted your friend request!";
                        break;
                }
            }
        }

        return '';
    }
}
