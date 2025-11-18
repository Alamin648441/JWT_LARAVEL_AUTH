<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Soft extends Model
{
    use SoftDeletes;
    protected $data = ['deleted_at'];

}
