<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable
{
    use Notifiable;
    use LaratrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'provider_id'
    ];

    const guestId = 2;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token'
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('socialLinks', function($builder) {
            $builder->with('socialLinks');
        });
    }

    public function posts(){
        return $this->hasMany('App\Post')->orderBy('id', 'desc');
    }
    public function comments(){
        return $this->hasMany('App\Comment');
    }
    public function socialLinks(){
        return $this->hasMany('App\SocialLinks');
    }
}
