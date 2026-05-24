<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CustomerMiddleware;
use App\Http\Middleware\KurirMiddleware;
use App\Http\Middleware\OwnerMiddleware;
use App\Http\Middleware\SupervisorMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'customer' => CustomerMiddleware::class,
            'kurir' => KurirMiddleware::class,
            'owner' => OwnerMiddleware::class,
            'supervisor' => SupervisorMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
    })
    ->create();
