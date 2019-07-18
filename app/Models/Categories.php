<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'id', 'name', 'deleted', 'parent_category_id', 'is_sub_category'
    ];
}

