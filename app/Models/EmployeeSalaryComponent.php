<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeSalaryComponent extends Model
{
    use HasFactory;

    protected $table = 'employee_salary_components';

    protected $fillable = [
        'employee_id',
        'salary_component_id',
        'amount',
        'effective_date',
        'end_date',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'effective_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        $today = now()->toDateString();

        return $query->where('effective_date', '<=', $today)
            ->where(function (Builder $query) use ($today) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', $today);
            });
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function salaryComponent(): BelongsTo
    {
        return $this->belongsTo(SalaryComponent::class);
    }
}
