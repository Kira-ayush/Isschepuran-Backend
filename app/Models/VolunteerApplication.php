<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VolunteerApplication extends Model
{
    protected $fillable = [
        'name', 'email', 'country_code', 'phone', 'area_of_interest', 'message', 'status',
    ];
}
