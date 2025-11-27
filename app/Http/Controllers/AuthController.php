<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Helpers\ResponseHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class AuthController extends Controller
{
    /**
     * Display the login view.
     */
    public function index(): Response
    {
        return Inertia::render('Auth/Login');
    }

    public function signupPage(): Response
    {
        return Inertia::render('Auth/Signup');
    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email address is required',
            'email.email' => 'Please enter a valid company email address',
            'password.required' => 'Access password is required',
        ]);

        try {

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors());
            }
            $user = User::where('email', $request->email)->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                return ResponseHelper::error("The provided credentials are incorrect.", 400);
            }

            // $request->session()->regenerate();
            $token = $user->createToken('auth-token')->plainTextToken;

            Log::info('User login', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return ResponseHelper::success([
                'user'    => $user,
                'token'   => $token,
            ], "Login successful");
        } catch (\Exception $e) {
            Log::error('Login failed', [
                'error' => $e->getMessage(),
            ]);
            return ResponseHelper::error('Failed to login', 500, [
                'exception' => $e->getMessage()
            ]);
        }
    }

    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email address is required',
            'email.email' => 'Please enter a valid company email address',
            'password.required' => 'Access password is required',
            'name.required' => 'Name is required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => UserRole::User->value,
                'email_verified_at' => now(),
                'password' => Hash::make($request->password),
                'remember_token' => Str::random(10),
            ]);
            return ResponseHelper::success($user, "Registered successful", 201);
        } catch (\Exception $e) {
            Log::error('User registration failed', [
                'error' => $e->getMessage(),
            ]);
            return ResponseHelper::error('Failed to register a new user', 500, [
                'exception' => $e->getMessage()
            ]);
        }
    }

    public function logout(Request $request)
    {
        // Auth::logout();

        // $request->session()->invalidate();
        // $request->session()->regenerateToken();
        try {
            //code...
            $request->user()->currentAccessToken()->delete();
            return ResponseHelper::success(null, "You have been securely logged out.");
        } catch (\Exception $e) {
            Log::error('User logout failed', [
                'error' => $e->getMessage(),
            ]);
            return ResponseHelper::error('Failed to logout', 500, [
                'exception' => $e->getMessage()
            ]);
        }
    }
}
