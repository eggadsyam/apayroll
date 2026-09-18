<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:departments,name,'.$this->route('department')?->id,
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama departemen wajib diisi.',
            'name.unique' => 'Nama departemen sudah digunakan.',
        ];
    }
}
