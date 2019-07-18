<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountsData extends Model
{
    protected $table = 'accounts_data';

    protected $fillable = [
        'id', 'company_id', 'qbo_id', 'year', 'values', 'report_type', 'date', 'deleted', 'deleted_at'
    ];
}









