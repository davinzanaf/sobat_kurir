<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('role') || session('role') !== 'admin') {
            return redirect()->route('admin.login')->withErrors([
                'auth' => 'Silakan login sebagai admin terlebih dahulu.',
            ]);
        }

        return $next($request);
    }
}
