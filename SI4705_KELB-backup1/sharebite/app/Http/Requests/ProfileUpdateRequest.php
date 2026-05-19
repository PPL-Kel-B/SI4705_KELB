<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'penanggung_jawab' => ['nullable', 'string', 'max:255'],
            'jumlah_anggota' => ['nullable', 'integer', 'min:1'],
            'current_password' => ['nullable', 'required_with:new_password', 'current_password'],
            'new_password' => [
                'nullable',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/', // Minimal 1 huruf kapital
                'regex:/[\d\W_]/' // Karakter unik atau nomor
            ],
            'foto_profil' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
