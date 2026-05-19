<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class KurirMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('role') || session('role') !== 'kurir') {
            return redirect()->route('login')->withErrors([
                'auth' => 'Silakan login sebagai kurir terlebih dahulu.',
            ]);
        }

        return $next($request);
    }
}
