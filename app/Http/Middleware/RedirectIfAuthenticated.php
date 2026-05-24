<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        if (session()->has('id_user') && session()->has('role')) {
            return $this->redirectByRole(session('role'));
        }

        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                if ($user && isset($user->role)) {
                    return $this->redirectByRole($user->role);
                }

                return redirect('/');
            }
        }

        return $next($request);
    }

    private function redirectByRole(string $role): Response
    {
        return match ($role) {
            'owner' => redirect('/owner/dashboard'),
            'supervisor' => redirect('/supervisor/dashboard'),
            'admin' => redirect('/admin/dashboard'),
            'kurir' => redirect('/kurir/dashboard'),
            'customer' => redirect('/customer/dashboard'),
            default => redirect('/'),
        };
    }
}
