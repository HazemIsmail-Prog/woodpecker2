<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'project_id',
        'start_date',
        'end_date',
        'row',
        'color'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
