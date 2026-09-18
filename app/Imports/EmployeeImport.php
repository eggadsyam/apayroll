<?php

namespace App\Imports;

use App\Models\Department;
use App\Models\Employee;
use App\Models\EmploymentStatus;
use App\Models\Position;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class EmployeeImport implements ToModel, WithHeadingRow, WithValidation
{
    private $departments;

    private $positions;

    private $employmentStatuses;

    public function __construct()
    {
        $this->departments = Department::pluck('id', 'name')->mapWithKeys(function ($item, $key) {
            return [strtolower($key) => $item];
        })->toArray();

        $this->positions = Position::pluck('id', 'name')->mapWithKeys(function ($item, $key) {
            return [strtolower($key) => $item];
        })->toArray();

        $this->employmentStatuses = EmploymentStatus::pluck('id', 'name')->mapWithKeys(function ($item, $key) {
            return [strtolower($key) => $item];
        })->toArray();
    }

    public function model(array $row): ?Model
    {
        // Resolve Department
        $departmentId = null;
        if (! empty($row['departemen'])) {
            $departmentName = strtolower(trim($row['departemen']));
            $departmentId = $this->departments[$departmentName] ?? null;
        }

        // Resolve Position
        $positionId = null;
        if (! empty($row['jabatan'])) {
            $positionName = strtolower(trim($row['jabatan']));
            $positionId = $this->positions[$positionName] ?? null;
        }

        // Resolve Employment Status
        $employmentStatusId = null;
        if (! empty($row['status_karyawan'])) {
            $statusName = strtolower(trim($row['status_karyawan']));
            $employmentStatusId = $this->employmentStatuses[$statusName] ?? null;
        }

        // Convert Excel Date
        $birthDate = null;
        if (! empty($row['tanggal_lahir'])) {
            $birthDate = is_numeric($row['tanggal_lahir'])
                ? Carbon::instance(Date::excelToDateTimeObject($row['tanggal_lahir']))->format('Y-m-d')
                : Carbon::parse($row['tanggal_lahir'])->format('Y-m-d');
        }

        $joinDate = null;
        if (! empty($row['tanggal_bergabung'])) {
            $joinDate = is_numeric($row['tanggal_bergabung'])
                ? Carbon::instance(Date::excelToDateTimeObject($row['tanggal_bergabung']))->format('Y-m-d')
                : Carbon::parse($row['tanggal_bergabung'])->format('Y-m-d');
        }

        return new Employee([
            'nik' => $row['nik'] ?? null,
            'name' => $row['nama'] ?? null,
            'gender' => strtolower($row['jenis_kelamin'] ?? 'l') == 'perempuan' || strtolower($row['jenis_kelamin'] ?? 'l') == 'p' ? 'female' : 'male',
            'birth_place' => $row['tempat_lahir'] ?? null,
            'birth_date' => $birthDate,
            'address' => $row['alamat'] ?? null,
            'phone' => $row['no_telepon'] ?? null,
            'email' => $row['email'] ?? null,
            'department_id' => $departmentId,
            'position_id' => $positionId,
            'employment_status_id' => $employmentStatusId,
            'join_date' => $joinDate ?? Carbon::now()->format('Y-m-d'),
            'basic_salary' => $row['gaji_pokok'] ?? 0,
            'bank_name' => $row['nama_bank'] ?? null,
            'bank_account_number' => $row['nomor_rekening'] ?? null,
            'bank_account_name' => $row['nama_rekening'] ?? null,
            'npwp' => $row['npwp'] ?? null,
            'bpjs_kesehatan' => $row['bpjs_kesehatan'] ?? null,
            'bpjs_ketenagakerjaan' => $row['bpjs_ketenagakerjaan'] ?? null,
            'status' => strtolower($row['status_aktif'] ?? 'active') === 'aktif' ? 'active' : (strtolower($row['status_aktif'] ?? 'active') === 'nonaktif' ? 'inactive' : 'active'),
        ]);
    }

    public function rules(): array
    {
        return [
            'nama' => 'required',
            'nik' => 'nullable|unique:employees,nik',
            'email' => 'nullable|email|unique:employees,email',
            'gaji_pokok' => 'required|numeric',
        ];
    }
}
