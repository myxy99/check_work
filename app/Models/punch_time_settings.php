<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class punch_time_settings extends Model
{
    public $timestamps = true;
    protected $table = 'punch_time_settings';
    protected $primaryKey = 'id';
    protected $guarded = [];
}
