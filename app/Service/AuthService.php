<?php

namespace App\Service;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UsersMeta;

class AuthService{

    public function register($request)
    {
        $user = User::create([
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password'])
        ]);

        $users_meta = UsersMeta::create([
            'user_id' => $user['id'],
            'role_id' => 5
        ]);

        $token = $user->createToken($user->email)->plainTextToken;

        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    public function login($request)
    {
        $user = User::query()->where('email', $request['email'])->first();

        if(! $user)
            return response()->json(['email' => "User not found"], 404);

        //  password
        if(! Hash::check($request['password'], $user->password))
            return response()->json(['password' => "Incorrect password"], 401);

        $token = $user->createToken($user->email)->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    public function logout()
    {
        $user = auth()->user();

        $user->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully, Logout'], 200);
    }
}

?>
