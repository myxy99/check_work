<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class login_records extends Model
{
    public $timestamps = true;
    protected $table = 'login_records';
    protected $primaryKey = 'id';
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user()
    {
        return $this->hasMany(users::class, 'id', 'user_id');
    }
}
