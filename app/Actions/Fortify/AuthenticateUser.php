<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Fortify;

class AuthenticateUser
{
    /**
     * Handle the authentication request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  callable  $next
     * @return mixed
     */
    public function handle(Request $request, callable $next)
    {
        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Check if user is trying to login with correct role
            $expectedRole = $this->getExpectedRoleFromUrl($request);
            
            if ($expectedRole && $user->role !== $expectedRole) {
                throw ValidationException::withMessages([
                    Fortify::username() => ['Bạn không có quyền đăng nhập với loại tài khoản này.'],
                ]);
            }

            return $next($request);
        }

        throw ValidationException::withMessages([
            Fortify::username() => [trans('auth.failed')],
        ]);
    }

    /**
     * Get expected role from URL
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    private function getExpectedRoleFromUrl(Request $request)
    {
        $url = $request->url();
        
        if (str_contains($url, '/login/student')) {
            return 'student';
        }
        
        if (str_contains($url, '/login/teacher')) {
            return 'teacher';
        }
        
        if (str_contains($url, '/login/admin')) {
            return 'admin';
        }
        
        return null;
    }
}
