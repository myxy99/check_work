<?php

namespace App\Models;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\Authenticatable;

class users extends \Illuminate\Foundation\Auth\User implements JWTSubject, Authenticatable
{
    public $timestamps = true;
    protected $rememberTokenName = NULL;
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $hidden = [
        'passwd',
    ];

    public function getJWTCustomClaims()
    {
        return [];
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->passwd;
    }
}
