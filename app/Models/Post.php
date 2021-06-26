<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

       /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id', 
    ];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function channel(){
        return $this->belongsTo('App\Models\Channel');
    }

    public function comment(){
        return $this->hasMany('App\Models\Comment', 'post_id');
    }

    public function advert(){
        return $this->hasOne('App\Models\Advert');
    }

    public function postimage(){
        return $this->hasMany('App\Models\PostImage');
    }
}
