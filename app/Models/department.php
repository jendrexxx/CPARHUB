<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class department extends Model
{
    protected $fillable = [
        'id',
        'department_name',
        'department_alias',
        'created_at',
        'updated_at'
    ];
}
