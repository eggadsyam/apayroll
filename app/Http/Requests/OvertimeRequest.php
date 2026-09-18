<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OvertimeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'notes' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'employee_id.required' => 'Karyawan wajib dipilih.',
            'date.required' => 'Tanggal wajib diisi.',
            'start_time.required' => 'Waktu mulai wajib diisi.',
            'end_time.required' => 'Waktu selesai wajib diisi.',
            'end_time.after' => 'Waktu selesai harus setelah waktu mulai.',
        ];
    }
}
