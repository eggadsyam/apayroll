<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'nik' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'birth_place' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:employees,email,'.$this->route('employee')?->id,
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'supervisor_id' => 'nullable|exists:employees,id',
            'employment_status_id' => 'required|exists:employment_statuses,id',
            'shift_id' => 'required|exists:shifts,id',
            'join_date' => 'required|date',
            'status' => 'required|in:active,inactive,resigned',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:255',
            'bank_account_name' => 'nullable|string|max:255',
            'npwp' => 'nullable|string|max:255',
            'bpjs_kesehatan' => 'nullable|string|max:255',
            'bpjs_ketenagakerjaan' => 'nullable|string|max:255',
            'ptkp_status' => 'required|string|in:TK/0,TK/1,TK/2,TK/3,K/0,K/1,K/2,K/3',
            'tax_method' => 'required|string|in:gross,gross_up,nett',
            'basic_salary' => 'required|numeric|min:0',
            'is_bpjs_kesehatan_active' => 'boolean',
            'is_bpjs_ketenagakerjaan_active' => 'boolean',
            'photo' => 'nullable|image|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'nik.required' => 'NIK wajib diisi.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'phone.required' => 'Nomor HP wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan.',
            'department_id.required' => 'Departemen wajib dipilih.',
            'position_id.required' => 'Jabatan wajib dipilih.',
            'employment_status_id.required' => 'Status wajib dipilih.',
            'shift_id.required' => 'Shift wajib dipilih.',
            'join_date.required' => 'Tanggal bergabung wajib diisi.',
            'status.required' => 'Status aktif wajib dipilih.',
            'basic_salary.required' => 'Gaji pokok wajib diisi.',
        ];
    }
}
