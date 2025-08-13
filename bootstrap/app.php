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
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'authUserAdmin' => App\Http\Middleware\AuthUserAdmin::class,
            'authUserDoctor' => App\Http\Middleware\AuthUserDoctor::class,
            'authUserSecretary' => App\Http\Middleware\AuthUserSecretary::class,
            'authUser' => App\Http\Middleware\AuthUser::class,
            'CheckRole' => App\Http\Middleware\CheckRole::class,
        ]); 
})
    
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
