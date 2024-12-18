<?php

namespace App\Http\Controllers\APIControllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function signup()
    {
        // return view('auth.signup');
    }

    public function register(Request $request){
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:App\Models\User',
            'password'=>'required|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'reader',
        ]);

        // $user->createToken('myAppToken')->plainTextToken;
        // $user->save();

        $response = [
            'name'=>$request->name,
            'email'=>request('email'),
        ];
        return response()->json($response);
    }

    public function login() {
        // return view('auth.login');
    }

    public function authenticate(Request $request) {
        $credentials = $request->validate([
            'email' =>'required|email',
            'password'=>'required|min:6'
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $token = $request->user()->createToken('MyAppToken')->plainTextToken;
            // $request->session()->regenerate();
            return response($token);
        }

        return response([
            'email' => 'The provided credentials do not match our records.',
        ], 401);
    }

    public function logout(Request $request) {
        // Auth::logout();
        $request->user()->tokens()->delete();
        return response('logout');
    }
}
