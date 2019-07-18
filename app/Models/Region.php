<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $table = 'region_table';

    protected $fillable = [
        'id', 'state'
    ];
}
