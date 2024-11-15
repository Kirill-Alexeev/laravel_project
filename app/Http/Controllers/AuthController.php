<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function signup()
    {
        return view('auth.signup');
    }

    public function register(Request $request){
        $request->validate([
            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required|min:6'
        ]);
        $response = [
            'name'=>$request->name,
            'email'=>request('email'),
            'password'=>request('password')
        ];
        return response()->json($response);
    }
}
