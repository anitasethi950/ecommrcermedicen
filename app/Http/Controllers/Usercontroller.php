<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class Usercontroller extends Controller
{
    // public function index()
    // {
    //     return view('login');
    // }

    public function login(Request $request)
    {
        
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);
    
       
        if (Auth::attempt($credentials)) {
            
            $request->session()->regenerate();
          
            return redirect()->route('admin.dashboard');
        }
    
       
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    public function dashboard()
    {
        return view('dashboard');
    }
    
}
