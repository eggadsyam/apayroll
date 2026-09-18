<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Overtime extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'start_time',
        'end_time',
        'hours',
        'rate',
        'amount',
        'status',
        'approved_by',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'hours' => 'decimal:2',
            'rate' => 'decimal:2',
            'amount' => 'decimal:2',
            'status' => 'string',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($overtime) {
            // Calculate hours
            $start = Carbon::parse($overtime->start_time);
            $end = Carbon::parse($overtime->end_time);
            $hours = $start->diffInMinutes($end) / 60;
            $overtime->hours = round($hours, 2);

            $settings = CompanySetting::getSettings();
            $employee = Employee::find($overtime->employee_id);

            if ($settings->overtime_formula === 'depnaker') {
                // Depnaker: 1st hour = 1.5x, next hours = 2x
                // Hourly wage = 1/173 x Basic Salary
                $hourlyWage = $employee->basic_salary / 173;
                $overtime->rate = round($hourlyWage, 2);

                if ($hours <= 1) {
                    $overtime->amount = round($hours * 1.5 * $hourlyWage, 2);
                } else {
                    $overtime->amount = round((1.5 * $hourlyWage) + (($hours - 1) * 2 * $hourlyWage), 2);
                }
            } else {
                // Flat rate
                $overtime->rate = $settings->overtime_rate_per_hour;
                $overtime->amount = round($hours * $settings->overtime_rate_per_hour, 2);
            }
        });
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeByPeriod(Builder $query, string $startDate, string $endDate): Builder
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
