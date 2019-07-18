<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecialityPercentage extends Model
{
    protected $table = 'speciality_percentage';

    protected $fillable = [
        'id', 'category_id', 'type_id', 'percentage', 'deleted',
    ];
}