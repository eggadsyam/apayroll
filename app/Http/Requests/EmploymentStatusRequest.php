<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmploymentStatusRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:employment_statuses,name,'.$this->route('employment_status')?->id,
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama status wajib diisi.',
            'name.unique' => 'Nama status sudah digunakan.',
        ];
    }
}
