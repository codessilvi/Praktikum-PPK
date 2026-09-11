<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Tampilkan form register
    public function showRegister()
    {
        return view('auth.register');
    }

    // Proses register — SRS-001
    public function register(Request $request)
    {
        // Validasi server-side, JANGAN dihapus meski sudah divalidasi di JS/HTML.
        // Validasi client-side gampang dilewati (misal via devtools/curl),
        // jadi validasi server ini yang jadi garis pertahanan sebenarnya.
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            // Password TIDAK PERNAH disimpan plaintext. Hash::make pakai bcrypt.
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    // Tampilkan form login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses login — SRS-001
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Auth::attempt otomatis mem-verifikasi password yang di-hash,
        // jadi kita tidak pernah membandingkan password mentah secara manual.
        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                // Pesan sengaja umum (tidak bilang "email tidak ditemukan" vs
                // "password salah") supaya orang tidak bisa menebak email
                // mana saja yang terdaftar di sistem (user enumeration).
                'email' => 'Email atau password salah.',
            ]);
        }

        // Regenerate session ID setelah login untuk mencegah session fixation attack.
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
