<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'id', 'first_name', 'last_name', 'email', 'company_name', 'is_email_verified', 'email_verified_at', 'confirmation_code','password'
, 'pwd', 'role_id', 'practices_count', 'profile_image', 'deleted', 'parent_id', 'status', 'active', 'is_subscription', 'user_page'];
}

