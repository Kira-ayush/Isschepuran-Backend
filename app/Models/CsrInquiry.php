<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CsrInquiry extends Model
{
    protected $fillable = [
        'organization_name', 'contact_person', 'email', 'country_code', 'phone',
        'budget_range', 'goals', 'status',
    ];
}
