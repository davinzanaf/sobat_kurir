<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SupervisorMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('id_user') || !in_array(session('role'), ['supervisor', 'owner'])) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Silakan login terlebih dahulu.',
                ]);
        }

        return $next($request);
    }
}
