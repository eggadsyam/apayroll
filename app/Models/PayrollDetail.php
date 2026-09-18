<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollDetail extends Model
{
    use HasFactory;

    protected $table = 'payroll_details';

    protected $fillable = [
        'payroll_id',
        'salary_component_id',
        'component_name',
        'component_type',
        'amount',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'component_type' => 'string',
        ];
    }

    public function scopeEarnings(Builder $query): Builder
    {
        return $query->where('component_type', 'earning');
    }

    public function scopeDeductions(Builder $query): Builder
    {
        return $query->where('component_type', 'deduction');
    }

    public function payroll(): BelongsTo
    {
        return $this->belongsTo(Payroll::class);
    }

    public function salaryComponent(): BelongsTo
    {
        return $this->belongsTo(SalaryComponent::class);
    }
}
