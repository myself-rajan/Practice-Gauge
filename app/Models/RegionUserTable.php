<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegionUserTable extends Model
{
    protected $table = 'region_user_table';

    protected $fillable = [
        'id', 'user_id','region_id','created_at','updated_at','deleted' 
    ];
}

