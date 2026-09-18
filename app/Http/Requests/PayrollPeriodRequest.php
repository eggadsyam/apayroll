<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PayrollPeriodRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->route('payroll_period')?->id;

        return [
            'month' => [
                'required',
                'integer',
                'between:1,12',
                Rule::unique('payroll_periods')->where(function ($query) {
                    return $query->where('year', $this->year);
                })->ignore($id),
            ],
            'year' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'payment_date' => 'required|date',
        ];
    }

    public function messages()
    {
        return [
            'month.required' => 'Bulan wajib diisi.',
            'month.unique' => 'Periode payroll untuk bulan dan tahun ini sudah ada.',
            'year.required' => 'Tahun wajib diisi.',
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'end_date.required' => 'Tanggal selesai wajib diisi.',
        ];
    }
}
