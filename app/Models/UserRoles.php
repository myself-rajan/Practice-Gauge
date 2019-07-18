<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRoles extends Model
{
    protected $table = 'roles';
    protected $fillable = ['id', 'name','deleted'];
}



