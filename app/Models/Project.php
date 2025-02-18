<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contract_date',
        'phone',
        'location',
        'quotation_number',
        'delivery_date',
        'installation_date',
        'type_of_work',
        'duration',
        'value',
        'status',
    ];

    protected $casts = [
        'contract_date' => 'date',
        'delivery_date' => 'date',
        'installation_date' => 'date',
        'value' => 'decimal:2'
    ];

    public function schedule()
    {
        return $this->hasOne(Schedule::class);
    }
} 