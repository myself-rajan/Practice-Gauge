<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request_email extends Model
{
    protected $table = 'request_email';

    protected $fillable = [
        'id', 'user_id', 'first_name', 'last_name', 'email','practices_name','message','created_at','updated_at', 'deleted'
    ];
}

