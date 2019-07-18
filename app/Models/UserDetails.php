<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    protected $table = 'user_details';

    protected $fillable = [
        'id', 'user_id','address','state','city','pincode','phone','know_about','know_about1','contact_person_name','prefer_communication','start_year','operatories','entity_type','types_of_practice','total_owner','total_employee','total_fte','is_milling_unit','has_aligner_services','deleted','steps','state_ids', 'region_id'
    ];
}