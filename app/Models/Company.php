<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'company';

    protected $fillable = [
        'id', 'user_id','name', 'qbo_realmid', 'qbo_access_token', 'qbo_refresh_token','qbo_version','qbo_state', 'last_sync', 'deleted', 'deleted_at','steps', 'is_report_verified'
    ];
}
