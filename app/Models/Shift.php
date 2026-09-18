<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = ['name', 'clock_in', 'clock_out'];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
