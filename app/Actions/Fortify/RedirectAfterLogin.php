<?php

namespace App\Actions\Fortify;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectAfterLogin
{
    /**
     * Handle the redirect after login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    public function handle(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return '/login';
        }

        // Redirect based on user role
        switch ($user->role) {
            case 'admin':
                return '/admin';
            case 'teacher':
                return '/teacher';
            case 'student':
                return '/student';
            default:
                return '/dashboard';
        }
    }
}
