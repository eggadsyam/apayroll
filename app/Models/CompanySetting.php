<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    use HasFactory;

    protected $table = 'company_settings';

    protected $fillable = [
        'company_name',
        'address',
        'phone',
        'email',
        'logo',
        'npwp',
        'overtime_rate_per_hour',
        'late_penalty_per_minute',
        'working_hours_per_day',
        'default_clock_in',
        'default_clock_out',
        'bpjs_kesehatan_capping',
        'bpjs_jp_capping',
        'overtime_formula',
    ];

    protected function casts(): array
    {
        return [
            'overtime_rate_per_hour' => 'decimal:2',
            'late_penalty_per_minute' => 'decimal:2',
            'bpjs_kesehatan_capping' => 'decimal:2',
            'bpjs_jp_capping' => 'decimal:2',
            'overtime_formula' => 'string',
        ];
    }

    public static function getSettings()
    {
        $settings = self::first();

        if (! $settings) {
            $settings = self::create([
                'company_name' => 'Perusahaan Default',
                'overtime_rate_per_hour' => 0,
                'late_penalty_per_minute' => 0,
            ]);
        }

        return $settings;
    }
}
