<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalaryComponentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => 'required|string|unique:salary_components,code,'.$this->route('salary_component')?->id,
            'name' => 'required|string|max:255',
            'type' => 'required|in:earning,deduction',
            'calculation_type' => 'required|in:fixed,percentage',
            'default_amount' => 'numeric|min:0',
            'taxable' => 'boolean',
            'active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function messages()
    {
        return [
            'code.required' => 'Kode wajib diisi.',
            'code.unique' => 'Kode sudah digunakan.',
            'name.required' => 'Nama komponen wajib diisi.',
            'type.required' => 'Tipe wajib dipilih.',
        ];
    }
}
