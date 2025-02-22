<?php

namespace App\Rules;

use App\Models\Schedule;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NoScheduleOverlap implements ValidationRule
{
    protected $scheduleId;
    protected $row;
    protected $startDate;
    protected $endDate;

    public function __construct($scheduleId, $row, $startDate, $endDate)
    {
        $this->scheduleId = $scheduleId;
        $this->row = $row;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $overlappingSchedule = Schedule::where('row', $this->row)
            ->when($this->scheduleId, function ($query) {
                return $query->where('id', '!=', $this->scheduleId);
            })
            ->where(function ($query) {
                $query->whereBetween('start_date', [$this->startDate, $this->endDate])
                    ->orWhereBetween('end_date', [$this->startDate, $this->endDate])
                    ->orWhere(function ($query) {
                        $query->where('start_date', '<=', $this->startDate)
                            ->where('end_date', '>=', $this->endDate);
                    });
            })
            ->first();

        if ($overlappingSchedule) {
            $fail("A schedule for {$overlappingSchedule->project->name} already exists in this row for the selected dates.");
        }
    }
} 