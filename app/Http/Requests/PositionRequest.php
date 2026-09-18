<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PositionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:positions,name,'.$this->route('position')?->id,
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama jabatan wajib diisi.',
            'name.unique' => 'Nama jabatan sudah digunakan.',
        ];
    }
}
