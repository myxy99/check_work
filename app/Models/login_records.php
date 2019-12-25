<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class login_records extends Model
{
    public $timestamps = true;
    protected $table = 'login_records';
    protected $primaryKey = 'id';
    protected $guarded = [];
}
