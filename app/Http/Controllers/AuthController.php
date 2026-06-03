<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Check if email exists first
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'No account has been registered with this email.']);
        }

        // Email exists, now try to authenticate
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['password' => 'The password is incorrect. Try again.']);
        }

        $request->session()->regenerate();
        return redirect()->intended('dashboard');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}