<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accounts extends Model
{
    protected $table = 'accounts';

    protected $fillable = [
        'id', 'account_name', 'account_num', 'account_type', 'account_subtype', 'qbo_id', 'is_subaccount', 'parent_ref', 'company_id', 'balance', 'active', 'deleted', 'deleted_at'
    ];
}

