<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('role') || session('role') !== 'customer') {
            return redirect()->route('login')->withErrors([
                'auth' => 'Silakan login sebagai customer terlebih dahulu.',
            ]);
        }

        return $next($request);
    }
}
