<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    //USER REGISTER API - POST
    public function register(Request $request) {

        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required|confirmed"
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        
        $user->save();

        return response()->json([
            "message"=> "User registered succesfully.",
            "user" => $user
        ], 201);

    }

    //USER LOGIN API - POST
    public function login(Request $request) {

        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        if(!$token = auth()->attempt(["email" => $request->email, "password" => $request->password])) {
            return response()->json([
                "message" => "Invalid credentials"
            ], 401);
        }

        return $this->createNewToken($token);
        
    }

    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL(),
            'user' => auth()->user()
        ]);
    }

    // USER PROFILE API = GET
    public function profile() {
        $user_data = auth()->user();

        return response()->json([
            "status" => 1,
            "message" => "User profile data",
            "data" => $user_data
        ]);
    }

    // USER LOGOUT API = GET
    public function logout() {
        auth()->logout();

        return response()->json([
            "message" => "User logged out"
        ]);
    }
}
