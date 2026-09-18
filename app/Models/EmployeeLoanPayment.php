<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeLoanPayment extends Model
{
    use HasFactory;

    protected $table = 'employee_loan_payments';

    protected $fillable = [
        'employee_loan_id',
        'payroll_id',
        'amount',
        'payment_date',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'payment_date' => 'date',
        ];
    }

    public function employeeLoan(): BelongsTo
    {
        return $this->belongsTo(EmployeeLoan::class);
    }

    public function payroll(): BelongsTo
    {
        return $this->belongsTo(Payroll::class);
    }
}
