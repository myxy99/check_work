<?php

namespace App\Models;


class users extends \Illuminate\Foundation\Auth\User implements \Illuminate\Contracts\Auth\Authenticatable
{
    public $timestamps = true;
    protected $rememberTokenName = NULL;
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $hidden = [
        'passwd',
    ];

    public function getAuthPassword()
    {
        return $this->passwd;
    }
}
