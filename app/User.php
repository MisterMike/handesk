<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Notification;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function scopeAdmin($query){
        return $query->whereAdmin(true);
    }

    public function tickets(){
        return $this->hasMany(Ticket::class)->with('requester','user','team');
    }

    public function teams(){
        return $this->belongsToMany(Team::class, "memberships")->withPivot('admin');
    }

    public function teamsTickets(){
        return $this->hasManyThrough(Ticket::class, Membership::class, "user_id","team_id")->with('requester','user','team');
    }

    public static function notifyAdmins( $notification ){
        Notification::send( User::admin()->get() , $notification);
    }
}