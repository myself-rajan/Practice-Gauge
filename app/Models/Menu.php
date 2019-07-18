<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu_settings';

    protected $fillable = [
        'id', 'menu_name', 'menu_order', 'parent_id','role_id','icons','routes'
    ];
}


