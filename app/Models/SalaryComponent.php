<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalaryComponent extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'type',
        'calculation_type',
        'default_amount',
        'taxable',
        'active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'default_amount' => 'decimal:2',
            'taxable' => 'boolean',
            'active' => 'boolean',
            'type' => 'string',
            'calculation_type' => 'string',
        ];
    }

    public function scopeEarnings(Builder $query): Builder
    {
        return $query->where('type', 'earning');
    }

    public function scopeDeductions(Builder $query): Builder
    {
        return $query->where('type', 'deduction');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    public function employeeSalaryComponents(): HasMany
    {
        return $this->hasMany(EmployeeSalaryComponent::class);
    }

    public function payrollDetails(): HasMany
    {
        return $this->hasMany(PayrollDetail::class);
    }
}
