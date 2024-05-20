<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserController extends Controller
{
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);


        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }


        $validated = $validator->validated();

        
        if (Auth::attempt($validated)) {
           
            $user = Auth::user();


            $payload = [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role, 
                'iat' => Carbon::now()->timestamp,
                'exp' => Carbon::now()->addHours(2)->timestamp,
            ];

            // Encode JWT token
            $token = JWT::encode($payload, env('JWT_SECRET_KEY'), 'HS256');

            return response()->json([
                'msg' => 'Token berhasil dibuat',
                'token' => $token
            ], 200);
        } else {
            return response()->json([
                'msg' => 'Email atau password yang dimasukkan salah'
            ], 401);
        }
    }
}
