<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([ // open the alias array to define custom middleware names
            'admin' => \App\Http\Middleware\IsAdmin::class, // assign the 'admin' label to our new IsAdmin class
        ]); // close the alias array
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
