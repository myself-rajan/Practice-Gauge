<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryMapping extends Model
{
    protected $table = 'category_mapping';

    protected $fillable = [
        'id', 'category_id', 'account_id', 'deleted'
    ];
}
