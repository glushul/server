<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegisterForm(){
        return view('auth.register');
    }

    public function register(Request $request){
        $user = $request->validate([
            'name'=>'required',
            'email'=> 'email|required|unique:App\Models\User',
            'password'=>'required|min:6'
        ]);
        $user = User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => Hash::make(request('password')),
        ]);   

        return redirect()->route('login');
    }

    public function showLoginForm(Request $request){
        return view('auth.login');
    }
    
    public function login(Request $request){
        $credentials = $request->validate([
            'email'=> 'email|required',
            'password'=>'required|min:6'
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return redirect('/');
        } 

        return back()->withErrors([
            'email' => 'The provided credentials do not math our records.'
        ]);
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
