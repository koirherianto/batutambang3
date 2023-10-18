<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use DB;

class AuthApiController extends AppBaseController
{
    private $response = [];

    public function register(RegisterUserRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = User::create([
                'nama_lengkap' => $request->nama_lengkap,
                'nama_panggilan' => $request->nama_panggilan,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            if ($request->role == 'mekanik') {
                $user->assignRole('mekanik');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Registrasi Berhasil'
            ], 200);


        } catch (Exception $e) {
            DB::rollBack();
            $this->response['success'] = false;
            $this->response['message'] = 'Registrasi Gagal. Terjadi kesalahan internal.';
            return response()->json($this->response, 200);
        }
    }


    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
            $this->response['success'] = false;
            $this->response['error'] = ['password' => ['Password Anda Salah']];
            return response()->json($this->response, 200);
        }

        $token = $user->createToken($request->device);

        $this->response['success'] = true;
        $this->response['data'] = [
            'user' => $user,
            'token' => $token->plainTextToken
        ];
        $this->response['message'] = 'Login Success';

        return response()->json($this->response, 200);
    }

    public function me()
    {
        $user = Auth::user();

        $this->unsetUser($user);
        $user->role = $user->roles->first()->name;

        $this->response['success'] = true;
        $this->response['data'] = [   
            'user' => $user,
        ];

        $this->response['message'] = 'Berhasil mendapatkan data user';

        return response()->json($this->response, 200);
    }

    public function updateProfil(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        $user->update($request->post());

        $this->unsetUser($user);

        $user->role = $user->roles->first()->name;

        $this->response['status'] = 'success';
        $this->response['data'] = [
            'user' => $user,
        ];
        $this->response['message'] = 'Update Profil Berhasil';

        return response()->json($this->response,200);
    }

    public function updatePassword(ChangePasswordRequest $request)
    {
        $user = Auth::user();
        $hash = Hash::check($request->password_lama, $user->password);

        if (!$hash) {
            $this->response['success'] = false;
            $this->response['error'] = [
                'password_lama' => ['Password Anda Tidak Sesuai']
            ];
            $this->response['message'] = 'Update Password Gagal. Password Anda Tidak Sesuai';
            
            return response()->json($this->response);
        }

        #Update the new Password
        $user->update([
            'password' => Hash::make($request->password_baru)
        ]);

        $this->response['success'] = true;
        $this->response['data'] = [];
        $this->response['message'] = 'Update Password Berhasil';

        return response()->json($this->response,200);
    }

    public function logout()
    {
        $logout = Auth::user()->currentAccessToken()->delete();

        // hapus semua token user
        Auth::user()->tokens->each(function ($token, $key) {
            $token->delete();
        });

        $this->response['success'] = true;
        $this->response['data'] = [];
        $this->response['message'] = 'Logout Success';

        return response()->json($this->response, 200);
    }
}
