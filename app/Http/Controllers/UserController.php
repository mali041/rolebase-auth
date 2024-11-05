<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function Signup(Request $req)
    {
        $validate_data = $req->validate([
            'name' => 'required|string|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed',
        ]);

        $user = User::create($validate_data);

        if ($user) {
            return redirect()->route('signin');
        }
    }

    public function Signin(Request $req)
    {
        $validate_data = $req->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($validate_data)) {
            return redirect()->route('home');
        }
    }

    public function signout()
    {
        Auth::logout();

        return redirect()->route('signin');
    }
}
