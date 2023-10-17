<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\AppBaseController;

class AuthApiController extends AppBaseController
{
    private $response = [];

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_relawan' => 'required|min:3|max:250',
            'contact_relawan' => 'required|numeric|unique:users,contact',
            'email_relawan' => 'required|max:250|email:dns|unique:users,email',
            'alamat_relawan' => 'required|min:4|max:250',
            'password_relawan' => 'required|min:6|max:250',
            'password_confirm_relawan' => 'required|same:password_relawan',
        ],[
            'name.min' => 'Nama minimal 3 karakter',
            'contact.numeric' => 'No hp harus berupa angka',
            'contact.unique' => 'No Hp Anda sudah terdaftar',
            'email.email' => 'Format Email anda salah',
            'email.unique' => 'Email Anda sudah terdaftar',
            'alamat.min' => 'Masukan alamat yang benar',
            'password.min' => 'Password Minimal 6 karakter',
            'password_confirm.same' => 'Kata sandi tidak cocok'
        ]);

        if ($validator->fails()) {
            $this->response['success'] = false;
            $this->response['error'] = $validator->errors();
            return response()->json($this->response, 200);
        }
        
        
        try{
            DB::beginTransaction();
            $user = User::create([
                'name' => $request->nama_relawan,
                'contact' => $request->contact_relawan,
                'email' => $request->email_relawan,
                'alamat' => $request->alamat_relawan,
                'password' => Hash::make($request->password_relawan)
            ]);
            
            $user->assignRole('admin-kandidat-free');
            
            DB::commit();
        }catch (Exception $e){
            DB::rollBack();
            $this->response['success'] = false;
            return response()->json($this->response, 200);
        }

        return $this->sendResponse([], 'Registrasi Success');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contact' => 'required|numeric|exists:users,contact',
            'password' => 'required|min:6|max:250',
        ],[
            'contact.exists' => 'Nomer anda tidak terdaftar',
            'contact.numeric' => 'No hp harus berupa angka',
            'password.min' => 'Password Minimal 6 karakter',
        ]);


        if ($validator->fails()) {
            $this->response['success'] = false;
            $this->response['error'] = $validator->errors();
            return response()->json($this->response, 200);
        }

        $user = User::where('contact', $request->contact)->first();

        //cek user ada
        if (empty($user)) {
            $this->response['success'] = false;
            $this->response['error'] = ['contact' => ['No Hp anda tidak terdaftar']];

            return response()->json($this->response, 200);
        }

        $hash = Hash::check($request->password, $user->password);
        if ($hash == false) {
            $this->response['success'] = false;
            $this->response['error'] = ['password' => ['Password Anda Salah']];
            return response()->json($this->response);
        }

        $token = $user->createToken($request->device);

        $this->response['success'] = true;
        $this->response['data'] = [
            'token' => $token->plainTextToken
        ];

        return response()->json($this->response, 200);
    }

    public function me()
    {
        $user = Auth::user();
        $dataRelawans = Relawan::where('id',Auth::user()->relawan->id)->first();
        $user = [
            "id" => $user->id,
            "name" => $user->name,
            "contact" => $user->contact,
            "email" => $user->email,
            "alamat" => $user->alamat,
            'role' => $user->getRoleNames(),
            'relawan_id' => $dataRelawans->id,
            'url_profil'=> $dataRelawans->getFirstMediaUrl(),
        ];
        $this->response['success'] = true;
        $this->response['data'] = $user;
        return response()->json($this->response, 200);
    }

    public function updatePassword(Request $request)
    {
        //cek kecocokan password
        // $hash = Hash::check($request->passwordLama, auth()->user()->password);
        $hash = Hash::check($request->passwordLama, Auth::user()->password);

        if (!$hash) {
            $this->response['status'] = 'failed';
            $this->response['error'] = ['passwordLama' => ['Password Anda Tidak Sesuai']];
            return response()->json($this->response);
        }

        $validator = Validator::make($request->all(), [
            'passwordLama' => 'required|min:6|max:255',
            'passwordBaru' => 'required|min:6|max:255',
            'passwordConfirm' => 'same:passwordBaru',
        ],[
            'passwordLama.min' => 'Kata Sandi minimal 6 karakter',
            'passwordBaru.min' => 'Kata Sandi minimal 6 karakter',
            'passwordConfirm.same' => 'Kata sandi tidak cocok'
        ]);

        if ($validator->fails()) {
            $this->response['status'] = 'failed';
            $this->response['error'] = $validator->errors();
            return response()->json($this->response,200);
        }

        #Update the new Password
        $user = User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->passwordBaru)
        ]);

        $this->response['status'] = 'success';
        $this->response['data'] = $user;

        return response()->json($this->response,200);
    }

    public function update(Request $request, User $user)
    {
        if($user->contact == $request->contact){
            $contactValidate = 'required|max:255';
        }else{
            $contactValidate = 'required|max:255|unique:users,contact';
        }
        

        if($user->email == $request->email){
            $emailValidate = 'required|max:250|email:dns';
        }else{
            $emailValidate = 'required|max:250|email:dns|unique:users,email';
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:4|max:255',
            'contact' => $contactValidate,
            'email' => $emailValidate,
            'alamat' => 'required|min:4|max:250',
            
        ]);

        if ($validator->fails()) {
            $this->response['status'] = 'failed';
            $this->response['error'] = $validator->errors();
            return response()->json($this->response,200);
        }

        $user->update($request->post());

        $dataRelawans = Relawan::where('id',Auth::user()->relawan->id)->first();
        $user['relawan_id'] = $dataRelawans->id;
        $user['url_profil'] = $dataRelawans->getFirstMediaUrl();

        $this->response['status'] = 'success';
        $this->response['data'] = $user;

        return response()->json($this->response,200);
    }

    public function logout()
    {
        $logout = Auth::user()->currentAccessToken()->delete();

        $this->response['success'] = true;
        return response()->json($this->response, 200);
    }
}
