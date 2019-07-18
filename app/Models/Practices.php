<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Practices extends Model
{
    protected $table = 'practices_type';

    protected $fillable = [
        'id', 'name'
    ];
}
