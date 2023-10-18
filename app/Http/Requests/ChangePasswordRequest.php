<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ChangePasswordRequest extends FormRequest
{
    public function authorize() : bool
    {
        return true;
    }

    public function rules() : array
    {
        return [
            'password_lama' => 'required|min:6|max:255',
            'password_baru' => 'required|min:6|max:255',
            'password_confirm' => 'required|same:password_baru',
        ];
    }

    public function messages() : array
    {
        return [
            'password_lama.required' => 'Kata sandi lama harus diisi.',
            'password_lama.min' => 'Kata sandi lama minimal 6 karakter.',
            'password_lama.max' => 'Kata sandi lama maksimal 255 karakter.',
            'password_baru.required' => 'Kata sandi baru harus diisi.',
            'password_baru.min' => 'Kata sandi baru minimal 6 karakter.',
            'password_baru.max' => 'Kata sandi baru maksimal 255 karakter.',
            'password_confirm.required' => 'Konfirmasi kata sandi baru harus diisi.',
            'password_confirm.same' => 'Konfirmasi kata sandi baru harus sama dengan kata sandi baru.',
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
