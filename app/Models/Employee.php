<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Hash;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_code',
        'nik',
        'name',
        'gender',
        'birth_place',
        'birth_date',
        'address',
        'phone',
        'email',
        'department_id',
        'position_id',
        'employment_status_id',
        'join_date',
        'resign_date',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'npwp',
        'bpjs_kesehatan',
        'bpjs_ketenagakerjaan',
        'basic_salary',
        'ptkp_status',
        'tax_method',
        'is_bpjs_kesehatan_active',
        'is_bpjs_ketenagakerjaan_active',
        'status',
        'photo',
        'shift_id',
        'supervisor_id',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'join_date' => 'date',
            'resign_date' => 'date',
            'basic_salary' => 'decimal:2',
            'gender' => 'string',
            'status' => 'string',
            'is_bpjs_kesehatan_active' => 'boolean',
            'is_bpjs_ketenagakerjaan_active' => 'boolean',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($employee) {
            if (empty($employee->employee_code)) {
                $lastEmployee = self::orderBy('id', 'desc')->first();
                $lastNumber = $lastEmployee ? intval(substr($lastEmployee->employee_code, 3)) : 0;
                $employee->employee_code = 'EMP'.str_pad((string) ($lastNumber + 1), 5, '0', STR_PAD_LEFT);
            }
        });

        static::created(function ($employee) {
            if ($employee->email) {
                $user = User::firstOrCreate(
                    ['email' => $employee->email],
                    [
                        'name' => $employee->name,
                        'password' => Hash::make('password'),
                        'employee_id' => $employee->id,
                    ]
                );

                if (! $user->hasRole('employee')) {
                    $user->assignRole('employee');
                }

                // If user already existed but wasn't linked to this employee
                if (empty($user->employee_id)) {
                    $user->update(['employee_id' => $employee->id]);
                }
            }
        });

        static::updated(function ($employee) {
            if ($employee->user) {
                if ($employee->isDirty('email') || $employee->isDirty('name')) {
                    $employee->user->update([
                        'name' => $employee->name,
                        'email' => $employee->email,
                    ]);
                }
            }
        });

        static::deleted(function ($employee) {
            if ($employee->user) {
                $employee->user->delete();
            }
        });
    }

    public function getFormattedSalaryAttribute(): string
    {
        return 'Rp'.number_format($this->basic_salary, 0, ',', '.');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeByDepartment(Builder $query, int $departmentId): Builder
    {
        return $query->where('department_id', $departmentId);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function employmentStatus(): BelongsTo
    {
        return $this->belongsTo(EmploymentStatus::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function overtimes(): HasMany
    {
        return $this->hasMany(Overtime::class);
    }

    public function leaves(): HasMany
    {
        return $this->hasMany(Leave::class);
    }

    public function salaryComponents(): HasMany
    {
        return $this->hasMany(EmployeeSalaryComponent::class);
    }

    public function employeeLoans(): HasMany
    {
        return $this->hasMany(EmployeeLoan::class);
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'supervisor_id');
    }

    public function subordinates(): HasMany
    {
        return $this->hasMany(Employee::class, 'supervisor_id');
    }
}
