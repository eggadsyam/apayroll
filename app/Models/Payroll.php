<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'payroll_period_id',
        'employee_id',
        'basic_salary',
        'total_earning',
        'total_deduction',
        'overtime_amount',
        'gross_salary',
        'net_salary',
        'status',
        'processed_by',
        'approved_by',
        'processed_at',
        'approved_at',
        'paid_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'basic_salary' => 'decimal:2',
            'total_earning' => 'decimal:2',
            'total_deduction' => 'decimal:2',
            'overtime_amount' => 'decimal:2',
            'gross_salary' => 'decimal:2',
            'net_salary' => 'decimal:2',
            'processed_at' => 'datetime',
            'approved_at' => 'datetime',
            'paid_at' => 'datetime',
            'status' => 'string',
        ];
    }

    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopeByPeriod(Builder $query, int $payrollPeriodId): Builder
    {
        return $query->where('payroll_period_id', $payrollPeriodId);
    }

    public function payrollPeriod(): BelongsTo
    {
        return $this->belongsTo(PayrollPeriod::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function payrollDetails(): HasMany
    {
        return $this->hasMany(PayrollDetail::class);
    }

    public function details(): HasMany
    {
        return $this->payrollDetails();
    }

    public function employeeLoanPayments(): HasMany
    {
        return $this->hasMany(EmployeeLoanPayment::class);
    }
}
