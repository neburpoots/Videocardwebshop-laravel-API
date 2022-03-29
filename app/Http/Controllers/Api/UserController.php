<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\JWTCodec;
use App\Models\RefreshToken;
use Exception;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    private $refresh_token_expiry;
    private $refresh_token;

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

        $this->generateRefresh_Token();

        return $this->createNewToken($token);
        
    }

    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'refresh_token' => $this->refresh_token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL(),
            'user' => auth()->user()
        ]);
    }

    // USER PROFILE API = GET
    public function profile() {
        $user_data = auth()->user();

        return response()->json([
            "status" => true,
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

    //ADDITIONAL FUNCTIONALITY WITHOUT A PACKAGE FOR LONG TERM REFRESH TOKENS
    protected function generateRefresh_Token() {

        $codec = new JWTCodec($_ENV["JWT_SECRET"]);
        $this->refresh_token_expiry = time() + 432000;
        $this->refresh_token = $codec->encode([
            "sub" => auth()->user()->id,
            "exp" => $this->refresh_token_expiry
        ]);

        $refresh_token = new RefreshToken();
        $refresh_token->token_hash = $this->refresh_token;
        $refresh_token->expires_at = $this->refresh_token_expiry;
        $refresh_token->save();
    }

    //CUSTOM CODE TO REFRESH THE ACCESS TOKEN AFTER EXPIRATION
    public function refreshWithToken(Request $request) {

        $request->validate([
            "refresh_token" => "required",
        ]);

        $refresh_token = $request->refresh_token;

        $codec = new JWTCodec($_ENV["JWT_SECRET"]);

        try {
            $payload = $codec->decode($refresh_token);
        } catch (Exception) {
            return response()->json([
                "status" => false,
                "message" => "Invalid token"
            ], 400);
        }

        $user_id = $payload["sub"];

        if (RefreshToken::where([
            "token_hash" => $refresh_token
        ])->doesntExist()) {
            return response()->json([
                "status" => false,
                "message" => "Invalid token (not on whitelist)"
            ], 400);
        }

        if (User::where([
            "id" => $user_id
        ])->doesntExist()) {
            return response()->json([
                "status" => false,
                "message" => "Invalid authentication"
            ], 401);
        }

        $user = User::find($user_id);

        DB::table('refresh_tokens')->where('token_hash', $refresh_token)->delete();

        if(!$token = auth()->login($user)) {
            return response()->json([
                "message" => "Invalid credentials"
            ], 401);
        }

        $this->generateRefresh_Token();

        return $this->createNewToken($token);
    }
}
