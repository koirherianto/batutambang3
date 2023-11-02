<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterUserRequest extends FormRequest
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
            'nama_lengkap' => 'required|string|min:4|max:255',
            'nama_panggilan' => 'required|string|min:4|max:45',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|max:255|min:6',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_lengkap.required' => 'Nama lengkap harus diisi',
            'nama_lengkap.min' => 'Nama lengkap minimal 4 karakter',
            'nama_lengkap.max' => 'Nama lengkap maksimal 250 karakter',
            'nama_panggilan.required' => 'Nama panggilan harus diisi',
            'nama_panggilan.min' => 'Nama panggilan minimal 4 karakter',
            'nama_panggilan.max' => 'Nama panggilan maksimal 250 karakter',
            'email.required' => 'Email harus diisi',
            'email.max' => 'Email maksimal 250 karakter',
            'email.email' => 'Format Email anda salah',
            'email.unique' => 'Email Anda sudah terdaftar',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password Minimal 6 karakter',
            'password.max' => 'Password Maksimal 250 karakter',
        ];
    }

    protected function failedValidation(Validator $validator) {
        // if ($request->is('api/*')) {
        //     $akses = 'akses melalui API';
        // } else {
        //     $akses = 'akses melalui web';
        // }
        
        $response = [
            'success' => false,
            'error' => $validator->errors(),
            'message' => 'Registrasi Gagal. Validasi Error'
        ];

        throw new HttpResponseException(response()->json($response, 200));
    }

}
