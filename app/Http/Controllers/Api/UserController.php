<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//use App\Http\Controllers\Api\request;
use App\Models\User;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;



class UserController extends Controller
{
     
    public function listPlayersRate()
    {
       return response()->json(['message' => 'List of all Players with average of percentage achievements.', User::select('name', 'rate')->get()], 200);
    }

    public function login(Request $request)
    {
        $this->validate($request,[
            'email' => 'required',
            'password' => 'required'
        ]);

        $data = [
                'email' => $request->email,
                'password' => $request->password
        ];

        if (Auth::attempt($data)) {
            $user = Auth::user();

            $token = $user->createToken('Personal Access Token')->accessToken;
            return response()->json(['message' => 'Successfully logged in', 'user' => $user->name, 'Personal Access Token' => $token], 200);
        }else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
    }

    public function register(Request $request)
    {
       
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required|min:4|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        if($validator->fails()){
            return response(['error' => $validator->errors(), 'Validation Error']);
        }else{
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
            $token = $user->createToken('Personal Access Token')->accessToken;

            $user->nickname = !empty($user->name) ? Str::slug($user->name) : 'anonymous';

            $user->save();

        return response()->json(['message' => 'Successfully user registered','user' => $user->name, 'email' => $user->email, 'Personal Access Token' => $token], 200);
        }
    }

    public function update(Request $request, $id)
    {
    $user = User::find($id);
        if ($user) {
            $user->name = $request->input('name');
            
            $user->save();
            return response()->json(['message' => 'Successfully Updated', 'user' => $user->name], 200);

        }else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }
     
    public function logout(Request $request)
    {
        $token = Auth::user()->token();
        $token->revoke();
        return response()->json([ 'message' => 'This session was logged out successfully'], 200);
    }
}
