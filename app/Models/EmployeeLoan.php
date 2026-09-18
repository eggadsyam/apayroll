<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmployeeLoan extends Model
{
    use HasFactory;

    protected $table = 'employee_loans';

    protected $fillable = [
        'employee_id',
        'loan_date',
        'amount',
        'installment',
        'remaining_balance',
        'total_installments',
        'paid_installments',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'loan_date' => 'date',
            'amount' => 'decimal:2',
            'installment' => 'decimal:2',
            'remaining_balance' => 'decimal:2',
            'status' => 'string',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function employeeLoanPayments(): HasMany
    {
        return $this->hasMany(EmployeeLoanPayment::class);
    }

    public function payments(): HasMany
    {
        return $this->employeeLoanPayments();
    }

    public function installments(): HasMany
    {
        return $this->employeeLoanPayments();
    }
}
