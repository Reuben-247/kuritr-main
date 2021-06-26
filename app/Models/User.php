<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id', 
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

public function userActivity(){
    return $this->hasMany('App\Models\UserActivityLog');
}

public function post(){
    return $this->hasMany('App\Models\Post');
}

public function notification(){
    return $this->hasMany('App\Models\Notification')->orderBy('created_at', 'DESC');
}

public function participationReward(){
    return $this->hasMany('App\Models\ParticipationReward');
}

public function participationStatus(){
    return $this->hasMany('App\Models\ParticipationStatus');
}

public function transactionHistory(){
    return $this->hasMany('App\Models\TransactionHistory');
}

public function wallet(){
    return $this->hasOne('App\Models\Wallet');
}

public function setting(){
    return $this->hasMany('App\Models\Setting');
}

public function subscribedChannel(){
    return $this->hasMany('App\Models\UserChannel');
}

public function images(){
    return $this->hasMany('App\Models\PostImage');
}


public function payment(){
    return $this->hasMany('App\Models\Payment');
}

public function chat(){
    return $this->hasMany('App\Models\Chat', 'receiver_id');
}

public function userType(){
    return $this->belongsTo('App\Models\UserType');
}

public function userLevel(){
    return $this->belongsTo('App\Models\UserLevel');
}

}
