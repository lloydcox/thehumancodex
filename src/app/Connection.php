<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Auth;
use function MongoDB\BSON\toJSON;

class Connection extends Model
{
    protected $fillable = [
        'user_id', 'connected_user_id', 'accepted', 'type', 'dom'
    ];

    public static function isConnected(User $connectedUser)
    {
        $user = Auth::user();

        return self::where(function($query) use ($connectedUser, $user) {
            $query
                ->where('user_id', $user->id)
                ->where('connected_user_id', $connectedUser->id);
        })->orWhere(function($query) use ($connectedUser, $user) {
            $query
                ->where('user_id', $connectedUser->id)
                ->where('connected_user_id', $user->id);
        })->exists();
    }

    public static function connectionWith(User $connectedUser)
    {
        $user = Auth::user();

        return self::where(function($query) use ($connectedUser, $user) {
            $query
                ->where('user_id', $user->id)
                ->where('connected_user_id', $connectedUser->id);
        })->orWhere(function($query) use ($connectedUser, $user) {
            $query
                ->where('user_id', $connectedUser->id)
                ->where('connected_user_id', $user->id);
        })->first();
    }

    /**
     * @param User $user
     * @return mixed
     */
    public static function allConnectedTo(User $user)
    {
        $invited = self::with('invited')
            ->where('user_id', $user->id)
            ->accepted()
            ->get()
            ->pluck('invited', 'invited.id');

        $accepted = self::with('sender')
            ->where('connected_user_id', $user->id)
            ->accepted()
            ->get()
            ->pluck('sender', 'sender.id');

        $merged =  $invited->merge($accepted);
        $index = [];
        foreach ($merged as $i => $connection){
            $totalConnections = false;
            foreach ($index as $connections) {
                if($connections->id == $connection->id){
                    $totalConnections = true;
                }
            }
            if(!$totalConnections) {
                $index[$i] = $connection;
            }
        }
        return $index;
    }


    public static function removeConnectionsBetween($user, $targetUser)
    {
        self::where('connected_user_id', $user->id)->where('user_id', $targetUser->id)->delete();
        self::where('connected_user_id', $targetUser->id)->where('user_id', $user->id)->delete();
    }

    public static function addConnectionBetween($user, $targetUser, $attrs = [])
    {
        self::create(array_merge([
            'user_id' => $user->id,
            'connected_user_id' => $targetUser->id,
            'accepted' => false,
            'dom' => Carbon::now(),
            'type' => 'public'
        ], $attrs));
    }

    public static function allRequestFor(User $user)
    {
        $requests = collect();

        $connections = self::with('sender')
            ->where('connected_user_id', $user->id)
            ->notAccepted()
            ->get();

        foreach ($connections as $connection) {
            $sender = $connection->sender;
            $requests->push([
                'id' => $connection->id,
                'created_at' => $connection->created_at->format('Y-m-d'),
                'user' => [
                    'url' => route('codex', $sender->username),
                    'first_name' => $sender->first_name,
                    'last_name' => $sender->last_name,
                    'avatar' => $sender->avatar
                ]
            ]);
        }

        return $requests;
    }

    public function invited()
    {
        return $this->hasOne(User::class, 'id','connected_user_id');
    }

    public function sender()
    {
        return $this->hasOne(User::class, 'id','user_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'connected_user_id', 'id');
    }

    public function scopeAccepted($query)
    {
        return $query->where('accepted', true);
    }

    public function scopeNotAccepted($query)
    {
        return $query->where('accepted', false);
    }

    public function groups()
    {
        return $this->hasMany('App\ConnectionGrouping');
    }
}
