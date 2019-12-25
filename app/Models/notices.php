<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class notices extends Model
{
    public $timestamps = true;
    protected $table = 'notices';
    protected $primaryKey = 'id';
    protected $guarded = [];
}
