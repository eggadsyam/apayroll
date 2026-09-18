<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PayrollPeriod extends Model
{
    use HasFactory;

    protected $table = 'payroll_periods';

    protected $fillable = [
        'name',
        'month',
        'year',
        'start_date',
        'end_date',
        'payment_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'payment_date' => 'date',
            'status' => 'string',
        ];
    }

    public function scopeByYear(Builder $query, int $year): Builder
    {
        return $query->where('year', $year);
    }

    public function getFormattedPeriodAttribute(): string
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $monthName = $months[$this->month] ?? '';

        return trim("{$monthName} {$this->year}");
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }
}
