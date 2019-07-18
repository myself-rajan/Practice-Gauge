<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'id', 'company_id', 'accounting_method', 'category', 'created_at', 'updated_at', 'deleted', 'deleted_at'
    ];
}


