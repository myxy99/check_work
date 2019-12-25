<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class notice_relations extends Model
{
    public $timestamps = true;
    protected $table = 'notice_relations';
    protected $primaryKey = 'id';
    protected $guarded = [];
}
