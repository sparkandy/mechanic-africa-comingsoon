<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'registration_number',
        'phone_number',
        'email',
        'technicians_count',
        'years_in_operation',
        'workshop_address',
        'state_city',
        'services_offered',
        'mobile_mechanic_service',
        'ip_address',
        'status',
    ];

    protected $casts = [
        'technicians_count' => 'integer',
        'years_in_operation' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
