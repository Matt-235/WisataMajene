<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }
    
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Redirect berdasarkan role
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'pengelola_wisata') {
                // Cek verifikasi untuk pengelola
                if ($user->status_verifikasi === 'terverifikasi') {
                    return redirect()->route('pengelola.dashboard');
                } else {
                    Auth::logout();
                    return back()->with('error', 'Akun Anda belum diverifikasi oleh admin.');
                }
            } else {
                return redirect()->route('home');
            }
        }
        
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }
    
    public function showRegistrationForm()
    {
        return view('auth.register');
    }
    
    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:wisatawan,pengelola_wisata',
            'no_telepon' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
        ]);
        
        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status_verifikasi' => $request->role === 'pengelola_wisata' ? 'belum_terverifikasi' : 'terverifikasi',
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat,
        ]);
        
        Auth::login($user);
        
        // Redirect berdasarkan role
        if ($user->role === 'pengelola_wisata') {
            return redirect()->route('home')->with('success', 'Registrasi berhasil! Tunggu verifikasi dari admin.');
        }
        
        return redirect()->route('home')->with('success', 'Registrasi berhasil!');
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}