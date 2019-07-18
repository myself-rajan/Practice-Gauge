<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCompanyMap extends Model
{
    protected $table = 'user_company_mapping';

    protected $fillable = [
        'id', 'user_id', 'company_id','deleted'
    ];
}
