<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxTerRate extends Model
{
    use HasFactory;

    protected $fillable = ['tax_ter_category_id', 'min_bruto', 'max_bruto', 'percentage'];

    protected function casts(): array
    {
        return [
            'min_bruto' => 'decimal:2',
            'max_bruto' => 'decimal:2',
            'percentage' => 'decimal:2',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TaxTerCategory::class, 'tax_ter_category_id');
    }
}
