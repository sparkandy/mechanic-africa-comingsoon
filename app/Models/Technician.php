<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technician extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'phone_number',
        'email',
        'state_city',
        'area_of_specialization',
        'years_in_operation',
        'work_type',
        'certification_training',
        'ip_address',
        'status',
    ];

    protected $casts = [
        'years_in_operation' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
