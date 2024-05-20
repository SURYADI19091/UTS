<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AdminMiddleware
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

            // Check the role from the decoded JWT token
            if ($jwtDecode->role === 'admin') {
                // Allow access for admin and user roles
                return $next($request);
            } else {
                return response()->json(['msg' => 'Akses ditolak karena peran pengguna bukan pemilik website.'], 403);
            }
        } catch (\Exception $e) {
            // JWT token decoding failed
            return response()->json(['msg' => 'Akses ditolak karena token tidak valid.'], 401);
        }
    }
}
