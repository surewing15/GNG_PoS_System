<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponses implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = Auth::user();

        // Redirect based on user role
        if ($user->role === 'Administrator') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'Clerk') {
            return redirect()->route('stocks.index');
        } elseif ($user->role === 'Cashier') {
            return redirect()->route('cashier.index');
        }

        // Default fallback
        return redirect()->route('/');
    }
}
