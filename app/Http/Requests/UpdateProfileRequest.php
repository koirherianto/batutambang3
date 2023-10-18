<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class UpdateProfileRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return true;
    }

    public function rules() : array
    {
        $user = Auth::user();
        $emailValidate = ($user->email == $this->input('email'))
            ? 'required|max:250|email:dns'
            : 'required|max:250|email:dns|unique:users,email,' . $user->id;

        return [
            'nama_lengkap' => 'required|min:4|max:255',
            'nama_panggilan' => 'required|min:4|max:255',
            'email' => $emailValidate,
        ];
    }

    public function messages() : array
    {
        return [
            'nama_lengkap.required' => 'Nama lengkap harus diisi.',
            'nama_lengkap.min' => 'Nama lengkap minimal 4 karakter.',
            'nama_lengkap.max' => 'Nama lengkap maksimal 255 karakter.',
            'nama_panggilan.required' => 'Nama panggilan harus diisi.',
            'nama_panggilan.min' => 'Nama panggilan minimal 4 karakter.',
            'nama_panggilan.max' => 'Nama panggilan maksimal 255 karakter.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 250 karakter.',
            'email.unique' => 'Email sudah terdaftar.',
        ];
    }

    protected function failedValidation(Validator $validator) : void {
        $response = [
            'success' => false,
            'error' => $validator->errors(),
            'message' => 'Update Profil Gagal. Validasi Error'
        ];

        throw new HttpResponseException(response()->json($response, 200));
    }
}
