<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{

    /*
    =========================
    FORM LOGIN
    =========================
    */
    public function login()
    {
        return view('auth.login');
    }

    /*
    =========================
    PROSES LOGIN
    =========================
    */
    public function authenticate(Request $request)
    {

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if(Auth::attempt($credentials)) {

            $request->session()->regenerate();

            return redirect('/sensor')
                ->with('success', 'Login berhasil');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah'
        ]);
    }

    /*
    =========================
    FORM REGISTER
    =========================
    */
    public function register()
    {
        return view('auth.register');
    }

    /*
    =========================
    SIMPAN REGISTER
    =========================
    */
    public function storeRegister(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5'
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'])
        ]);

        return redirect('/login')
            ->with('success', 'Register berhasil');
    }

    /*
    =========================
    LOGOUT
    =========================
    */
    public function logout(Request $request)
    {

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }

}