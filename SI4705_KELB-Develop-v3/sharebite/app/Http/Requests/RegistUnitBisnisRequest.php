<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegistUnitBisnisRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'Nama_Usaha' => 'required|string|max:100',
            'Jenis_Usaha' => 'required|string|max:100',
            'Alamat' => 'required|string|max:100',
            'NIB_File' => 'nullable|file|mimes:pdf,jpg,jpeg|max:5120',
            'Nomor_hp' => 'required|string|max:20',
            'Email' => 'required|email|max:45|unique:unit_bisnis,Email',
            'Password' => 'required|string|max:8|regex:/[A-Z]/|regex:/[\d\W]/',
            'Latitude' => 'nullable|string',
            'Longitude' => 'nullable|string',
        ];
    }
}
