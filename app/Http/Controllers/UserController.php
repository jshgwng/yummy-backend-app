<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function registerUser(Request $request)
    {
        $request->validate(['name' => 'required', 'email' => 'required', 'password' => 'required|min:8']);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        $token = $user->createToken('auth')->plainTextToken;

        return response(
            [
                'status' => true,
                'message' => 'Registration successful.',
                'data' => [
                    'user' => $user,
                    'token' => $token
                ]
            ]
        );
    }

    public function loginUser(Request $request)
    {
        try {
            $request->validate(['email' => 'required', 'password' => 'required']);

            if (User::where('email', $request->email)->exists()) {
                if (!Auth::attempt($request->all())) {
                    return response(
                        [
                            'status' => false,
                            'message' => 'Invalid username/password.'
                        ]
                    );
                }
                /** @var \App\Models\User $user */
                $user = Auth::user();
                $token = $user->createToken('auth')->plainTextToken;
                return response(
                    [
                        'status' => true,
                        'message' => 'Registration successful.',
                        'data' => [
                            'user' => $user,
                            'token' => $token
                        ]
                    ]
                );
            }
            return response(
                [
                    'status' => false,
                    'message' => 'Invalid username/password.'
                ]
            );
        } catch (\Throwable $th) {
            return response(
                [
                    'status' => false,
                    'error' => $th->getMessage()
                ]
            );
        }
    }
}
