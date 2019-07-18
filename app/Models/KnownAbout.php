<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnownAbout extends Model
{
    protected $table = 'known_about';

    protected $fillable = [
        'id', 'name',  'deleted'
    ];
}
