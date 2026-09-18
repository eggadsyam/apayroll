<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeaveType extends Model
{
    use HasFactory;

    protected $table = 'leave_types';

    protected $fillable = [
        'name',
        'max_days',
        'description',
    ];

    public function leaves(): HasMany
    {
        return $this->hasMany(Leave::class);
    }
}
