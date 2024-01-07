<?php

namespace App\Http\Controllers\Api;

use App\Models\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware(
            'auth:api',
            [
                'except' =>
                [
                    'login',
                    'register'
                ]
            ]
        );
    }

    public function register(Request $request)
    {

        $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:6',
            ]
        );


        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ]);


        $token = Auth::login($user);

        return response()->json([
            'status' => 'succes',
            'message' => 'user Registred successfully !!',
            'user' => $user,
            'token' => $token
        ]);
    }

    public function login(Request $request)
    {

        $request->validate(
            [

                'email' => 'required',
                'password' => 'required',
            ]
        );

        $credentials = $request->only('email', 'password');


        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Logged Failed'
            ]);
        }



        return response()->json([
            'status' => 'succes',
            'message' => 'Logged in successfully !!',
            'token' => $token
        ]);
    }
}
