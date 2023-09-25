<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "email" => "required|email",
                "password" => "required"
            ]
        );

        if ($validator->fails()) {
            return send_error('Validation error', $validator->errors(), 422);
        }

        $credentials = $request->only('email', 'password');

        if(Auth::attempt($credentials)){
            $user = Auth::user();
            $data['name'] = $user->name;
            $data['access_token'] = $user->createToken('accessToken')->accessToken;

            return send_response('You are succesfully logged in', $data);
        }else{
            return send_error('Unauthorised', '', 401);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "name" => "required|min:4",
                "email" => "required|email|unique:users",
                "password" => "required|min:6",
            ]
        );

        if ($validator->fails()) {
            return send_error('Validation error', $validator->errors(), 422);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $data = [
                'name' => $user->name,
                'email' => $user->email
            ];

            return send_response('User registration success', $data);
        } catch (\Throwable $th) {
            return send_error($th->getMessage(), $th->getCode());
        }
    }
}
