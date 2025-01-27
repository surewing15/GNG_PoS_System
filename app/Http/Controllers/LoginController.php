<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Validate the user credentials
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            // Regenerate session to prevent session fixation attacks
            $request->session()->regenerate();

            // Check the user's role and redirect accordingly
            $role = Auth::user()->role;

            if ($role === 'Administrator') {
                return redirect()->intended('/dashboard'); // Admin dashboard
            } elseif ($role === 'Clerk') {
                return redirect()->intended('/stocks'); // Clerk dashboard
            } elseif ($role === 'Cashier') {
                return redirect()->intended('/cashier/pos'); // Cashier dashboard
            }

            // Fallback redirection (optional)
            return redirect()->intended('/login');
        }

        // Redirect back with an error message if authentication fails
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
}
