<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'clock_in',
        'clock_out',
        'status',
        'late_minutes',
        'working_hours',
        'overtime_hours',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'working_hours' => 'decimal:2',
            'overtime_hours' => 'decimal:2',
            'status' => 'string',
        ];
    }

    public function scopeByPeriod(Builder $query, string $startDate, string $endDate): Builder
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function scopeByEmployee(Builder $query, int $employeeId): Builder
    {
        return $query->where('employee_id', $employeeId);
    }

    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
