<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function listPlayersRate()
    {
        if (!Auth::user()->hasRole('admin')) {
            return response()->json(['error' => 'You are unauthorized to access this resource'], 401);
        }

        $players = User::select('nickname', 'rate')->get();

        return response()->json([
            'message' => 'List of all Players with average of percentage achievements.',
            'players' => $players
        ], 200);
    }

    public function login(Request $request)
    {
        
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::check() ) {
            return response()->json(['error' => 'The user is already logged in...'], 401);
        }

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->accessToken;

            return response()->json([
                'message' => 'Successfully logged in',
                'user' => $user->name,
                'token' => $token
            ], 200);
        } else {
            return response()->json([
                'message' => 'The provided credentials do not match our records.',
                'error' => 'Unauthorized',
            ], 401);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5',
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error'], 302);
        } else {
            $user = User::create([
                'name' => $request->name,
                'nickname' => !empty($request->name) ? Str::slug($request->name) : 'anonymous',
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10)
            ])->assignRole('player');
            // 
            $token = $user->createToken('auth_token')->accessToken;

            return response()->json([
                'message' => 'Successfully user registered',
                'user' => $user->nickname,
                'id' => $user->id,
                'email' => $user->email,
                'Token' => $token
            ], 200);
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if ( !Auth::user()->hasRole('admin') && Auth::user()->id != $id) {
            return response()->json(['error' => $id], 401);
            // return response()->json(['error' => 'This user is unauthorized'], 401);
        }

        $user->name = $request->input('name');
        $user->nickname = !empty($request->input('name')) ? Str::slug($request->input('name')) : 'anonymous';
        $user->save();

        return response()->json(['message' => 'User updated successfully', 'user' => $user->nickname], 200);
    }

    public function logout(Request $request)
    {
        $token = Auth::user()->token();
        $token->revoke();

        return response()->json(['message' => 'This session was logged out successfully'], 200);

    }

    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'This user was not found'], 404);
        }

        if (!Auth::user()->hasRole('admin') && Auth::user()->id != $user->id) {
            return response()->json(['error' => 'You are unauthorized to view this user'], 401);
        }

        return response()->json($user, 200);
    }
    
}
