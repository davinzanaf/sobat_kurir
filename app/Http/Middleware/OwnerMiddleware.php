<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OwnerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('id_user') || session('role') !== 'owner') {
            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Silakan login terlebih dahulu.',
                ]);
        }

        return $next($request);
    }
}
