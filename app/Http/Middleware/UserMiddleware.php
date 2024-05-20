<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $jwt = $request->bearerToken();

        // Check if JWT token is provided
        if (!$jwt) {
            return response()->json(['msg' => 'Akses ditolak. Silakan login terlebih dahulu.'], 401);
        }

        try {
            // Decode JWT token
            $jwtDecode = JWT::decode($jwt, new Key(env('JWT_SECRET_KEY'), 'HS256'));

            // Log the decoded token for debugging
            Log::info('Decoded JWT: ', (array)$jwtDecode);

            // Check the role from the decoded JWT token
            if (isset($jwtDecode->role) && $jwtDecode->role === 'user') {
                // Allow access for user role
                return $next($request);
            } else {
                return response()->json(['msg' => 'Akses ditolak karena peran pengguna tidak valid.'], 403);
            }
        } catch (\Exception $e) {
            // Log the error message
            Log::error('JWT Decode Error: ' . $e->getMessage());

            // JWT token decoding failed
            return response()->json(['msg' => 'Akses ditolak karena token tidak valid.'], 401);
        }
    }
}
