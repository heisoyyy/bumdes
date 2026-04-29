<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        $credentials = [
            'username'  => $request->username,
            'password'  => $request->password,
            'is_active' => 'aktif',
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Update last login
            Auth::user()->update([
                'last_login' => now(),
            ]);

            return match (Auth::user()->role) {
                'pengurus'    => redirect()->route('pengurus.dashboard')
                    ->with('success', 'Selamat datang, ' . Auth::user()->name),
                'kepala_desa' => redirect()->route('kepaladesa.dashboard')
                    ->with('success', 'Selamat datang, ' . Auth::user()->name),
                'masyarakat'  => redirect()->route('masyarakat.dashboard')
                    ->with('success', 'Selamat datang, ' . Auth::user()->name),
                default       => redirect()->route('login'),
            };
        }

        return back()
            ->withInput($request->only('username'))
            ->withErrors([
                'username' => 'Username atau password salah, atau akun tidak aktif',
            ]);
    }
}