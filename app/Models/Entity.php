<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    protected $table = 'entity_type';

    protected $fillable = [
        'id', 'name'
    ];
}
