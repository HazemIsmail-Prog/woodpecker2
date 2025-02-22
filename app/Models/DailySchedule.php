<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailySchedule extends Model
{
    protected $fillable = ['date', 'project_id', 'employee_ids'];

    protected $casts = [
        'date' => 'date',
        'employee_ids' => 'array'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function employees()
    {
        return Employee::whereIn('id', $this->employee_ids ?? [])->get();
    }
}
