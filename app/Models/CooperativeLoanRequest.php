<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CooperativeLoanRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'amount',
        'tenor_months',
        'notes',
        'status',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
        ];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
