<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmploymentStatus extends Model
{
    use HasFactory;

    protected $table = 'employment_statuses';

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the employees for the employment status.
     */
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
