<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle()
    {
        return redirect()->away(Socialite::driver('google')->stateless()->redirect()->getTargetUrl());
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Check if the user exists in our database
        $user = User::where('email', $googleUser->email)->first();

        if (!$user) {
            // Create a new user if not exists
            $user = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'password' => bcrypt(Str::random(24)), // Generate random password
                'role' => 'user', // Default role
            ]);
        } else {
            // Update the existing user's google_id
            $user->update([
                'google_id' => $googleUser->id,
            ]);
        }

        // Define the expiration time of the token
        $tokenExpiry = Carbon::now()->addHour()->timestamp;

        // Generate JWT token
        $jwtToken = JWT::encode([
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => 'user',
            'exp' => $tokenExpiry, // Token expiration time
            // Add more claims as needed
        ], env('JWT_SECRET_KEY'), 'HS256');

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'role' => $user->role,
                'google_id' => $user->google_id,
            ],
            'token' => $jwtToken,
            'token_type' => 'Bearer',
            'expires_in' => 3600, // Token expiration in seconds
        ]);
    }
}
