<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $fields = $request->validate([
           'email' => 'required|email',
           'password' => 'required'
        ]);

        $user = User::where('email', $fields['email'])->first();

        if(!$user || !Hash::check($fields['password'], $user->password))
        {
            return response([
                'message' => 'Invalid Credentials'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = $user->createToken('secret-token')->plainTextToken;

        $response = [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ];

        return response()->json($response, Response::HTTP_OK);

    }



    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|confirmed|string'
        ]);

        $user = User::create([
           'name' => $fields['name'],
           'email' => $fields['email'],
           'password' => bcrypt($fields['password']),
        ]);

        $token = $user->createToken('secret-token')->plainTextToken;

        $response = [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ];

        return response()->json($response, Response::HTTP_CREATED);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return [
            'message' => 'Logged Out',
        ];
    }
}
