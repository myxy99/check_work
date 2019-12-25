<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class punch_time_records extends Model
{
    public $timestamps = true;
    protected $table = 'punch_time_records';
    protected $primaryKey = 'id';
    protected $guarded = [];
}
