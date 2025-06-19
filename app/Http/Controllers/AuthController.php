<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        if ($request->user_type == User::TYPE_ADMIN) {
            return response()->json(['error' => 'You are not allowed to create an admin user.'], 403); 
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'profile_photo_base64' => 'nullable|string',
            'identification_type' => 'required|string|max:255|in:' . User::IDENTIFICATION_COMMERCIAL . ',' . User::IDENTIFICATION_NATIONAL_ID,
            'identification_number' => 'required|string|max:255',
            'user_type' => 'required|string|max:255|in:' . User::TYPE_VOLUNTEER . ',' . User::TYPE_ORGANIZATION,
            //'is_active' => 'required|boolean',
            //'active_until' => 'required|date',
            //'is_approved' => 'required|boolean',
            //'approved_at' => 'nullable|date',
            //'points' => 'required|numeric',
            'skills' => 'nullable|string',
            'details' => 'nullable|string',
        ]);
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'profile_photo_base64' => $request->profile_photo_base64,
            'identification_type' => $request->identification_type,
            'identification_number' => $request->identification_number,
            'user_type' => $request->user_type,
            //'is_active' => $request->is_active,
            //'active_until' => $request->active_until,
            //'is_approved' => $request->is_approved,
            //'approved_at' => $request->approved_at,
            //'points' => $request->points,
            'skills' => $request->skills,
            'details' => $request->details,
        ]);

        try {
            $token = JWTAuth::fromUser($user);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        return response()->json([
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            } 
            
            if (!auth()->user()->is_active) {
                return response()->json(['error' => 'Your user is inactive'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        $user = auth()->user();
    
        return response()->json([
            'token' => $token,
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'user' => $user,
            //'expires_in' => auth('api')->factory()->getTTL() * 60,
        ]);
    }

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to logout, please try again'], 500);
        }

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function getUser()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }
            return response()->json($user);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to fetch user profile'], 500);
        }
    }

    public function updateUser(Request $request)
    {
        try {
            $user = Auth::user();
            $user->fill($request->only(['name', 'email']));
            $user->save();
            return response()->json($user);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to update user'], 500);
        }
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return response()->json([
            'token' => JWTAuth::refresh(),
        ]);
    }
}