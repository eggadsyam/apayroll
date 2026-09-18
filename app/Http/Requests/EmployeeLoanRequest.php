<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeLoanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'amount' => 'required|numeric|min:0',
            'installment' => 'required|numeric|min:0',
            'total_installments' => 'required|integer|min:1',
            'loan_date' => 'required|date',
            'notes' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'employee_id.required' => 'Karyawan wajib dipilih.',
            'amount.required' => 'Jumlah pinjaman wajib diisi.',
            'installment.required' => 'Jumlah cicilan wajib diisi.',
            'total_installments.required' => 'Total bulan cicilan wajib diisi.',
            'loan_date.required' => 'Tanggal pinjaman wajib diisi.',
        ];
    }
}
