<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistIndividuRequest extends FormRequest
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
            'nama_lengkap' => ['required', 'string', 'max:100', 'regex:/^[\pL\s]+$/u'],
            'no_hp' => ['required', 'string', 'regex:/^(\+62|0)[0-9]{9,13}$/', 'unique:users,no_hp'],
            'email'        => 'required|email|max:100|unique:users,email',
            'password'     => 'required|string|min:8',
        ];
    }
}