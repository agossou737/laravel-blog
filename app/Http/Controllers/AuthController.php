<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //Register user

    public function register(Request $request)
    {
        $attrs = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed'
        ]);

        //create user
        $user = User::create([
            'name' =>  $attrs['name'],
            'email' =>  $attrs['email'],
            'password' =>  bcrypt($attrs['password']),
        ], 200);

        //return user & token in response

        return response([
            'user' => $user,
            'token' => $user->createToken('secret')->plainTextToken
        ]);
    }

    // login user

    public function login(Request $request)
    {
        $attrs = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        // attemps user
        if (!Auth::attempt($attrs)) {
            return response([
                'message' => 'Adresse email ou mot de passe incorrect '
            ], 403);
        }

        //return user & token in response

        return response([
            'user' => auth()->user(),
            'token' => auth()->user()->createToken('secret')->plainTextToken
        ], 200);
    }

    //logout user

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        /* print_r(auth()->user());
        //auth()->user()->tokens()->remove(); */
    }

    //get user details

    public function user()
    {
        return response([
            'user' => auth()->user()
        ], 200);
    }

    //update user

    public function updateUser(Request $request)
    {
        $attrs = $request->validate([
            "name" => "required|string"
        ]);

        $image = $this->savedImage($request->image, "profiles");

        auth()->user()->update([
            "name" => $attrs["name"],
            "image" => $image
        ]);

        return response([
            "message" => "User updated",
            'user' => auth()->user()
        ], 200);
    }
}
