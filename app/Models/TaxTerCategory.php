<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaxTerCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'ptkp_list'];

    public function rates(): HasMany
    {
        return $this->hasMany(TaxTerRate::class);
    }
}
