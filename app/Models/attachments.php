<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class attachments extends Model
{
    public $timestamps = true;
    protected $table = 'attachments';
    protected $primaryKey = 'id';
    protected $guarded = [];
}
